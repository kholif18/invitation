<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::orderBy('is_default', 'desc')
            ->orderBy('name')
            ->paginate(12);
        
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:templates',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'template_zip' => 'required|file|mimes:zip|max:51200', // Max 50MB
            'version' => 'required|string|max:50',
            'author' => 'nullable|string|max:255',
            'author_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'config' => 'nullable|json',
        ]);

        try {
            // Handle file uploads
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')->store('templates/thumbnails', 'public');
            }
            
            if ($request->hasFile('preview_image')) {
                $validated['preview_image'] = $request->file('preview_image')->store('templates/previews', 'public');
            }
            
            // Extract and process template zip
            $templateZip = $request->file('template_zip');
            $templateSlug = Str::slug($validated['name']);
            $extractPath = storage_path('app/temp/templates/' . $templateSlug);
            
            // Create extraction directory
            if (!file_exists($extractPath)) {
                mkdir($extractPath, 0755, true);
            }
            
            // Extract zip
            $zip = new ZipArchive;
            if ($zip->open($templateZip->path()) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract template zip file');
            }
            
            // Look for blade template files
            $bladeFile = $this->findBladeTemplate($extractPath);
            if (!$bladeFile) {
                throw new \Exception('No blade template file found in the zip');
            }
            
            // Copy template files to proper location
            $templatePath = resource_path('views/templates/' . $templateSlug);
            if (!file_exists($templatePath)) {
                mkdir($templatePath, 0755, true);
            }
            
            // Copy blade files
            $this->copyDirectory($extractPath . '/views', $templatePath);
            
            // Copy assets
            $assetPath = public_path('assets/templates/' . $templateSlug);
            if (!file_exists($assetPath)) {
                mkdir($assetPath, 0755, true);
            }
            
            if (file_exists($extractPath . '/assets')) {
                $this->copyDirectory($extractPath . '/assets', $assetPath);
            }
            
            // Store file paths
            $validated['blade_file'] = 'templates.' . $templateSlug . '.index';
            $validated['css_file'] = 'assets/templates/' . $templateSlug . '/css/style.css';
            $validated['js_file'] = 'assets/templates/' . $templateSlug . '/js/script.js';
            $validated['slug'] = $templateSlug;
            
            // Parse config if provided as JSON string
            if ($request->filled('config')) {
                $validated['config'] = json_decode($request->config, true);
            }
            
            // Set boolean flags
            $validated['is_active'] = $request->has('is_active');
            $validated['is_default'] = $request->has('is_default');
            
            // Create template
            $template = Template::create($validated);
            
            // Clean up temp files
            $this->deleteDirectory($extractPath);
            
            return redirect()->route('admin.templates.index')
                ->with('success', 'Template uploaded successfully!');
                
        } catch (\Exception $e) {
            // Clean up on error
            if (isset($extractPath) && file_exists($extractPath)) {
                $this->deleteDirectory($extractPath);
            }
            
            return back()->with('error', 'Failed to upload template: ' . $e->getMessage());
        }
    }

    public function show(Template $template)
    {
        return view('admin.templates.show', compact('template'));
    }

    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:templates,name,' . $template->id,
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'version' => 'required|string|max:50',
            'author' => 'nullable|string|max:255',
            'author_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'config' => 'nullable|json',
        ]);

        // Handle file uploads
        if ($request->hasFile('thumbnail')) {
            if ($template->thumbnail) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('templates/thumbnails', 'public');
        }
        
        if ($request->hasFile('preview_image')) {
            if ($template->preview_image) {
                Storage::disk('public')->delete($template->preview_image);
            }
            $validated['preview_image'] = $request->file('preview_image')->store('templates/previews', 'public');
        }
        
        // Parse config if provided
        if ($request->filled('config')) {
            $validated['config'] = json_decode($request->config, true);
        }
        
        // Set boolean flags
        $validated['is_active'] = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');
        
        $template->update($validated);
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Template updated successfully!');
    }

    public function destroy(Template $template)
    {
        // Check if template is used by any invitation
        if ($template->invitations()->count() > 0) {
            return back()->with('error', 'Cannot delete template that is being used by invitations.');
        }
        
        // Delete template files
        $templatePath = resource_path('views/templates/' . $template->slug);
        if (file_exists($templatePath)) {
            $this->deleteDirectory($templatePath);
        }
        
        $assetPath = public_path('assets/templates/' . $template->slug);
        if (file_exists($assetPath)) {
            $this->deleteDirectory($assetPath);
        }
        
        // Delete uploaded images
        if ($template->thumbnail) {
            Storage::disk('public')->delete($template->thumbnail);
        }
        if ($template->preview_image) {
            Storage::disk('public')->delete($template->preview_image);
        }
        
        $template->delete();
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Template deleted successfully!');
    }

    public function setDefault(Template $template)
    {
        Template::where('is_default', true)->update(['is_default' => false]);
        $template->update(['is_default' => true]);
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Default template set successfully!');
    }

    public function preview(Template $template)
    {  
        // Create dummy invitation data for preview
        $invitation = new \App\Models\Invitation();
        $invitation->groom_full_name = 'Bagas Prakoso';
        $invitation->groom_nickname = 'Bagas';
        $invitation->groom_father_name = 'Supriyadi';
        $invitation->groom_mother_name = 'Sri Mulyani';
        $invitation->groom_address = 'Jl. Merdeka No. 123, Jakarta';
        $invitation->groom_photo = null;
        
        $invitation->bride_full_name = 'Siti Nurhaliza';
        $invitation->bride_nickname = 'Siti';
        $invitation->bride_father_name = 'Hasanudin';
        $invitation->bride_mother_name = 'Fatimah';
        $invitation->bride_address = 'Jl. Sudirman No. 456, Jakarta';
        $invitation->bride_photo = null;
        
        $invitation->has_akad = true;
        $invitation->akad_date = now()->addDays(30);
        $invitation->akad_time = now()->addDays(30)->setTime(9, 0);
        $invitation->akad_location = 'Masjid Agung, Jakarta Pusat';
        
        $invitation->has_reception = true;
        $invitation->receptions = [
            [
                'name' => 'Resepsi Pernikahan',
                'date' => now()->addDays(30)->format('Y-m-d'), 
                'location' => 'Hotel Indonesia, Jakarta Pusat'
            ]
        ];
        
        $invitation->has_gift = true;
        $invitation->gift_image = null;
        $invitation->bank_accounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)', 
                'account_name' => 'Bambang & Siti', 
                'account_number' => '1234567890'
            ],
            [
                'bank_name' => 'Bank Mandiri', 
                'account_name' => 'Bambang & Siti', 
                'account_number' => '0987654321'
            ]
        ];
        
        $invitation->has_gallery = true;
        // Dummy gallery images (gunakan placeholder)
        $invitation->gallery_photos = [];
        $invitation->gallery_videos = [];
        
        $invitation->is_wish_active = true;
        $invitation->maps = [
            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.822561!3d-6.194741!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0xd8f6b9e1e2f5f2e1!2sJakarta!5e0!3m2!1sid!2sid!4v1699999999999!5m2!1sid!2sid'
        ];
        $invitation->template = $template;
        $invitation->template_settings = $template->settings ?? [];
        
        // Dummy wishes for preview
        $wishes = collect([
            (object)[
                'guest_name' => 'Ibu Dewi',
                'message' => 'Selamat ya nak! Semoga menjadi keluarga yang sakinah mawaddah warahmah.',
                'attendance' => 'yes',
                'attendance_count' => 2,
                'created_at' => now()
            ],
            (object)[
                'guest_name' => 'Pak Budi',
                'message' => 'Barakallah, semoga langgeng dan bahagia selalu!',
                'attendance' => 'yes',
                'attendance_count' => 3,
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'guest_name' => 'Susi',
                'message' => 'Selamat ya teman! Doa yang terbaik untuk kalian berdua.',
                'attendance' => 'maybe',
                'attendance_count' => 1,
                'created_at' => now()->subDay()
            ]
        ]);
        
        $guest = null;
        $isPreview = true; // Flag untuk preview mode
        
        // Use the template's view
        if ($template->blade_file && view()->exists($template->blade_file)) {
            return view($template->blade_file, compact('invitation', 'wishes', 'guest', 'isPreview'));
        }
        
        // Fallback jika template view tidak ditemukan
        return view('admin.templates.preview-fallback', compact('template'));
    }

    public function download(Template $template)
    {
        // This would create a zip of the template files for download
        // Implementation depends on your needs
        return redirect()->route('admin.templates.index')
            ->with('info', 'Download feature coming soon.');
    }

    public function select()
    {
        $templates = Template::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
        
        return view('admin.templates.select', compact('templates'));
    }

    private function findBladeTemplate($path)
    {
        $bladeFiles = glob($path . '/views/*.blade.php');
        if (count($bladeFiles) > 0) {
            return $bladeFiles[0];
        }
        
        // Check subdirectories
        $subdirs = glob($path . '/views/*', GLOB_ONLYDIR);
        foreach ($subdirs as $subdir) {
            $bladeFiles = glob($subdir . '/*.blade.php');
            if (count($bladeFiles) > 0) {
                return $bladeFiles[0];
            }
        }
        
        return null;
    }

    private function copyDirectory($source, $destination)
    {
        if (!is_dir($source)) {
            return false;
        }
        
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $items = scandir($source);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            $sourcePath = $source . '/' . $item;
            $destPath = $destination . '/' . $item;
            
            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
        
        return true;
    }

    private function deleteDirectory($path)
    {
        if (!is_dir($path)) {
            return false;
        }
        
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            $itemPath = $path . '/' . $item;
            if (is_dir($itemPath)) {
                $this->deleteDirectory($itemPath);
            } else {
                unlink($itemPath);
            }
        }
        
        return rmdir($path);
    }

    public function previewIframe(Template $template)
    {
        // Create dummy invitation data for preview
        $invitation = new \App\Models\Invitation();
        $invitation->groom_full_name = 'Bagas Prakoso';
        $invitation->groom_nickname = 'Bagas';
        $invitation->groom_father_name = 'Supriyadi';
        $invitation->groom_mother_name = 'Sri Mulyani';
        $invitation->groom_address = 'Jl. Merdeka No. 123, Jakarta';
        $invitation->groom_photo = null;
        
        $invitation->bride_full_name = 'Siti Nurhaliza';
        $invitation->bride_nickname = 'Siti';
        $invitation->bride_father_name = 'Hasanudin';
        $invitation->bride_mother_name = 'Fatimah';
        $invitation->bride_address = 'Jl. Sudirman No. 456, Jakarta';
        $invitation->bride_photo = null;
        
        $invitation->has_akad = true;
        $invitation->akad_date = now()->addDays(30);
        $invitation->akad_time = now()->addDays(30)->setTime(9, 0);
        $invitation->akad_location = 'Masjid Agung, Jakarta Pusat';
        
        $invitation->has_reception = true;
        $invitation->receptions = [
            [
                'name' => 'Resepsi Pernikahan',
                'date' => now()->addDays(30)->format('Y-m-d'), 
                'location' => 'Hotel Indonesia, Jakarta Pusat'
            ]
        ];
        
        $invitation->has_gift = true;
        $invitation->gift_image = null;
        $invitation->bank_accounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)', 
                'account_name' => 'Bambang & Siti', 
                'account_number' => '1234567890'
            ]
        ];
        
        $invitation->has_gallery = true;
        $invitation->gallery_photos = [];
        $invitation->gallery_videos = [];
        
        $invitation->is_wish_active = true;
        $invitation->maps = [
            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.822561!3d-6.194741!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0xd8f6b9e1e2f5f2e1!2sJakarta!5e0!3m2!1sid!2sid!4v1699999999999!5m2!1sid!2sid'
        ];
        $invitation->template = $template;
        $invitation->template_settings = $template->settings ?? [];
        
        $wishes = collect([]);
        $guest = null;
        $isPreview = true;
        
        // Use the template's view
        if ($template->blade_file && view()->exists($template->blade_file)) {
            return view($template->blade_file, compact('invitation', 'wishes', 'guest', 'isPreview'));
        }
        
        return view('admin.templates.preview-fallback', compact('template'));
    }
}
