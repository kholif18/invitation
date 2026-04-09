<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::withCount(['guests', 'wishes'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.invitations.index', compact('invitations'));
    }

    public function create(Request $request)
    {
        // Check if template is selected
        $templateId = $request->query('template');
        
        if (!$templateId) {
            // Redirect to template selection page
            return redirect()->route('admin.templates.select')
                ->with('info', 'Please select a template first.');
        }
        
        $template = Template::where('id', $templateId)
            ->where('is_active', true)
            ->firstOrFail();
        
        return view('admin.invitations.create', compact('template'));
    }

    public function store(Request $request)
    {
        Log::info('Store request received', $request->all());
        
        try {
            $validated = $this->validateInvitation($request);
            Log::info('Validation passed', $validated);
            
            // Verify template exists and is active
            $template = Template::where('id', $validated['template_id'])
                ->where('is_active', true)
                ->first();
            
            if (!$template) {
                Log::error('Template not found or inactive: ' . $validated['template_id']);
                return back()->with('error', 'Invalid or inactive template selected.');
            }
            
            Log::info('Template found: ' . $template->id);
            
            DB::beginTransaction();
            
            // Handle file uploads
            if ($request->hasFile('groom_photo')) {
                $validated['groom_photo'] = $this->uploadFile($request, 'groom_photo', 'invitations/groom');
                Log::info('Groom photo uploaded: ' . ($validated['groom_photo'] ?? 'null'));
            }
            
            if ($request->hasFile('bride_photo')) {
                $validated['bride_photo'] = $this->uploadFile($request, 'bride_photo', 'invitations/bride');
                Log::info('Bride photo uploaded: ' . ($validated['bride_photo'] ?? 'null'));
            }
            
            if ($request->hasFile('gift_image')) {
                $validated['gift_image'] = $this->uploadFile($request, 'gift_image', 'invitations/gift');
                Log::info('Gift image uploaded: ' . ($validated['gift_image'] ?? 'null'));
            }
            
            // Handle gallery uploads
            if ($request->hasFile('gallery_photos')) {
                $validated['gallery_photos'] = $this->uploadMultipleFiles($request->file('gallery_photos'), 'invitations/gallery/photos');
                Log::info('Gallery photos uploaded: ' . count($validated['gallery_photos']));
            }
            
            if ($request->hasFile('gallery_videos')) {
                $validated['gallery_videos'] = $this->uploadMultipleFiles($request->file('gallery_videos'), 'invitations/gallery/videos');
                Log::info('Gallery videos uploaded: ' . count($validated['gallery_videos']));
            }
            
            // Set boolean flags - PERHATIKAN: checkbox yang tidak di-check tidak akan terkirim
            $validated['has_akad'] = $request->has('akadNikahToggle') || $request->has('akad_date');
            $validated['has_reception'] = $request->has('resepsiToggle') || ($request->has('receptions') && !empty($request->receptions));
            $validated['has_gift'] = $request->has('giftToggle') || true; // Default true
            $validated['has_gallery'] = $request->has('galleryToggle') || $request->hasFile('gallery_photos');
            $validated['is_wish_active'] = $request->has('is_wish_active');
            
            Log::info('Boolean flags set', [
                'has_akad' => $validated['has_akad'],
                'has_reception' => $validated['has_reception'],
                'has_gift' => $validated['has_gift'],
                'has_gallery' => $validated['has_gallery'],
                'is_wish_active' => $validated['is_wish_active']
            ]);
            
            // Process receptions
            if ($request->has('receptions') && is_array($request->receptions)) {
                $validated['receptions'] = array_values($request->receptions);
                Log::info('Receptions processed: ' . json_encode($validated['receptions']));
            }
            
            // Process maps
            if ($request->has('maps') && is_array($request->maps)) {
                $validated['maps'] = array_values(array_filter($request->maps));
                Log::info('Maps processed: ' . json_encode($validated['maps']));
            }
            
            // Process bank accounts
            if ($request->has('bank_accounts') && is_array($request->bank_accounts)) {
                $validated['bank_accounts'] = array_values($request->bank_accounts);
                Log::info('Bank accounts processed: ' . json_encode($validated['bank_accounts']));
            }
            
            // Create invitation
            $invitation = Invitation::create($validated);
            Log::info('Invitation created with ID: ' . $invitation->id);
            
            DB::commit();
            
            $message = $validated['status'] === 'published' 
                ? 'Wedding invitation created successfully! You can now add guests.' 
                : 'Wedding invitation saved as draft! You can add guests later.';
            
            return redirect()->route('admin.invitations.show', $invitation)
                ->with('success', $message);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create invitation: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to create invitation: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Invitation $invitation)
    {
        $invitation->load(['guests', 'wishes']);
        $totalGuests = $invitation->guests()->count();
        $sentGuests = $invitation->guests()->where('is_sent', true)->count();
        $confirmedGuests = $invitation->guests()->where('attendance_status', 'confirmed')->count();
        $totalWishes = $invitation->wishes()->count();
        
        return view('admin.invitations.show', compact('invitation', 'totalGuests', 'sentGuests', 'confirmedGuests', 'totalWishes'));
    }

    public function edit(Invitation $invitation)
    {
        $template = $invitation->template;
        return view('admin.invitations.edit', compact('invitation', 'template'));
    }

    public function update(Request $request, Invitation $invitation)
    {
        $validated = $this->validateInvitation($request, $invitation->id);
        
        DB::beginTransaction();
        
        try {
            // Handle file uploads (only if new files are provided)
            if ($request->hasFile('groom_photo')) {
                $this->deleteFile($invitation->groom_photo);
                $validated['groom_photo'] = $this->uploadFile($request, 'groom_photo', 'invitations/groom');
            }
            
            if ($request->hasFile('bride_photo')) {
                $this->deleteFile($invitation->bride_photo);
                $validated['bride_photo'] = $this->uploadFile($request, 'bride_photo', 'invitations/bride');
            }
            
            if ($request->hasFile('gift_image')) {
                $this->deleteFile($invitation->gift_image);
                $validated['gift_image'] = $this->uploadFile($request, 'gift_image', 'invitations/gift');
            }
            
            // Handle gallery updates
            if ($request->hasFile('gallery_photos')) {
                $this->deleteMultipleFiles($invitation->gallery_photos);
                $validated['gallery_photos'] = $this->uploadMultipleFiles($request->file('gallery_photos'), 'invitations/gallery/photos');
            }
            
            if ($request->hasFile('gallery_videos')) {
                $this->deleteMultipleFiles($invitation->gallery_videos);
                $validated['gallery_videos'] = $this->uploadMultipleFiles($request->file('gallery_videos'), 'invitations/gallery/videos');
            }
            
            // Set boolean flags
            $validated['has_akad'] = $request->has('akadNikahToggle');
            $validated['has_reception'] = $request->has('resepsiToggle');
            $validated['has_gift'] = $request->has('giftToggle');
            $validated['has_gallery'] = $request->has('galleryToggle');
            $validated['is_wish_active'] = $request->has('is_wish_active');
            
            // Process receptions
            if ($request->has('receptions')) {
                $validated['receptions'] = array_values($request->receptions);
            }
            
            // Process maps
            if ($request->has('maps')) {
                $validated['maps'] = array_values(array_filter($request->maps));
            }
            
            // Process bank accounts
            if ($request->has('bank_accounts')) {
                $validated['bank_accounts'] = array_values($request->bank_accounts);
            }
            
            // Update invitation
            $invitation->update($validated);
            
            DB::commit();
            
            $message = $validated['status'] === 'published' 
                ? 'Wedding invitation updated successfully!' 
                : 'Wedding invitation updated as draft successfully!';
            
            return redirect()->route('admin.invitations.show', $invitation)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update invitation: ' . $e->getMessage());
        }
    }

    public function destroy(Invitation $invitation)
    {
        DB::beginTransaction();
        
        try {
            // Delete all associated files
            $this->deleteFile($invitation->groom_photo);
            $this->deleteFile($invitation->bride_photo);
            $this->deleteFile($invitation->gift_image);
            $this->deleteMultipleFiles($invitation->gallery_photos);
            $this->deleteMultipleFiles($invitation->gallery_videos);
            
            $invitation->delete();
            
            DB::commit();
            
            return redirect()->route('admin.invitations.index')
                ->with('success', 'Invitation deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete invitation: ' . $e->getMessage());
        }
    }

    public function duplicate(Invitation $invitation)
    {
        $newInvitation = $invitation->replicate();
        $newInvitation->slug = Str::slug($invitation->groom_full_name . '-' . $invitation->bride_full_name . '-' . uniqid());
        $newInvitation->status = 'draft';
        $newInvitation->created_at = now();
        $newInvitation->save();
        
        return redirect()->route('admin.invitations.edit', $newInvitation)
            ->with('success', 'Invitation duplicated successfully!');
    }

    public function customizeTemplate(Invitation $invitation)
    {
        $template = $invitation->template;
        $settings = $invitation->template_settings ?? [];
        
        // Merge with default template settings
        $defaultSettings = $template->settings ?? [];
        $currentSettings = array_merge($defaultSettings, $settings);
        
        return view('admin.invitations.customize-template', compact('invitation', 'template', 'currentSettings'));
    }

    /**
     * Update template settings
     */
    public function updateTemplateSettings(Request $request, Invitation $invitation)
    {
        $validated = $request->validate([
            'enable_music' => 'boolean',
            'custom_music' => 'nullable|file|mimes:mp3,audio/mpeg|max:10240',
            'opening_greeting' => 'nullable|string',
            'invitation_text' => 'nullable|string',
            'footer_message' => 'nullable|string',
            'closing_greeting' => 'nullable|string',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'accent_color' => 'nullable|string',
            'text_color' => 'nullable|string',
            'primary_font' => 'nullable|string',
            'title_font' => 'nullable|string',
            'layout_style' => 'nullable|string',
            'animation' => 'nullable|string',
            'gallery_layout' => 'nullable|string',
            'gallery_items_per_row' => 'nullable|integer',
            'show_countdown' => 'boolean',
            'show_gift_section' => 'boolean',
            'show_comment_section' => 'boolean',
        ]);
        
        // Handle custom music upload
        if ($request->hasFile('custom_music')) {
            // Delete old music if exists
            if ($invitation->template_settings && isset($invitation->template_settings['music_path'])) {
                $this->deleteFile($invitation->template_settings['music_path']);
            }
            
            $musicPath = $this->uploadFile($request, 'custom_music', 'invitations/music');
            $validated['music_path'] = $musicPath;
        }
        
        // Merge with existing settings
        $existingSettings = $invitation->template_settings ?? [];
        $newSettings = array_merge($existingSettings, $validated);
        
        $invitation->update([
            'template_settings' => $newSettings
        ]);
        
        return redirect()->route('admin.invitations.show', $invitation)
            ->with('success', 'Template settings updated successfully!');
    }

    private function validateInvitation(Request $request, $id = null)
    {
        $rules = [
            'template_id' => 'required|exists:templates,id',
            'groom_full_name' => 'required|string|max:255',
            'groom_nickname' => 'required|string|max:255',
            'groom_father_name' => 'required|string|max:255',
            'groom_mother_name' => 'required|string|max:255',
            'groom_address' => 'required|string',
            'bride_full_name' => 'required|string|max:255',
            'bride_nickname' => 'required|string|max:255',
            'bride_father_name' => 'required|string|max:255',
            'bride_mother_name' => 'required|string|max:255',
            'bride_address' => 'required|string',
            'status' => 'required|in:draft,published',
        ];
        
        // Custom error messages
        $messages = [
            // Required field messages
            'template_id.required' => 'Template harus dipilih.',
            'template_id.exists' => 'Template yang dipilih tidak valid.',
            'groom_full_name.required' => 'Nama lengkap mempelai pria wajib diisi.',
            'groom_nickname.required' => 'Nama panggilan mempelai pria wajib diisi.',
            'groom_father_name.required' => 'Nama ayah mempelai pria wajib diisi.',
            'groom_mother_name.required' => 'Nama ibu mempelai pria wajib diisi.',
            'groom_address.required' => 'Alamat mempelai pria wajib diisi.',
            'bride_full_name.required' => 'Nama lengkap mempelai wanita wajib diisi.',
            'bride_nickname.required' => 'Nama panggilan mempelai wanita wajib diisi.',
            'bride_father_name.required' => 'Nama ayah mempelai wanita wajib diisi.',
            'bride_mother_name.required' => 'Nama ibu mempelai wanita wajib diisi.',
            'bride_address.required' => 'Alamat mempelai wanita wajib diisi.',
            'status.required' => 'Status invitation wajib dipilih.',
            'status.in' => 'Status invitation tidak valid.',
            
            // File validation messages
            'groom_photo.image' => 'File foto mempelai pria harus berupa gambar.',
            'groom_photo.mimes' => 'Format foto mempelai pria harus: jpeg, png, jpg, gif, webp, bmp.',
            'groom_photo.max' => 'Ukuran foto mempelai pria maksimal 5MB.',
            'groom_photo.file' => 'File foto mempelai pria tidak valid.',
            
            'bride_photo.image' => 'File foto mempelai wanita harus berupa gambar.',
            'bride_photo.mimes' => 'Format foto mempelai wanita harus: jpeg, png, jpg, gif, webp, bmp.',
            'bride_photo.max' => 'Ukuran foto mempelai wanita maksimal 5MB.',
            'bride_photo.file' => 'File foto mempelai wanita tidak valid.',
            
            'gift_image.image' => 'File gambar gift harus berupa gambar.',
            'gift_image.mimes' => 'Format gambar gift harus: jpeg, png, jpg, gif, webp, bmp.',
            'gift_image.max' => 'Ukuran gambar gift maksimal 5MB.',
            'gift_image.file' => 'File gambar gift tidak valid.',
            
            'gallery_photos.*.image' => 'File gallery harus berupa gambar.',
            'gallery_photos.*.mimes' => 'Format gambar gallery harus: jpeg, png, jpg, gif, webp, bmp.',
            'gallery_photos.*.max' => 'Ukuran gambar gallery maksimal 10MB per file.',
            'gallery_photos.*.file' => 'File gallery tidak valid.',
            
            'gallery_videos.*.mimetypes' => 'Format video gallery harus: mp4, mpeg, quicktime.',
            'gallery_videos.*.max' => 'Ukuran video gallery maksimal 200MB per file.',
            
            // Akad validation messages
            'akad_date.required' => 'Tanggal akad nikah wajib diisi.',
            'akad_date.date' => 'Format tanggal akad nikah tidak valid.',
            'akad_time.required' => 'Jam akad nikah wajib diisi.',
            'akad_location.required' => 'Lokasi akad nikah wajib diisi.',
            
            // Reception validation messages
            'receptions.*.name.required' => 'Nama resepsi wajib diisi.',
            'receptions.*.date.required' => 'Tanggal resepsi wajib diisi.',
            'receptions.*.date.date' => 'Format tanggal resepsi tidak valid.',
            'receptions.*.location.required' => 'Lokasi resepsi wajib diisi.',
        ];
        
        // File validations dengan format gambar yang lebih lengkap
        $imageMimes = 'jpeg,png,jpg,gif,webp,bmp,svg';
        $imageMaxSize = '5120'; // 5MB
        $galleryImageMaxSize = '10240'; // 10MB
        $videoMaxSize = '204800'; // 200MB
        $videoMimes = 'mp4,mpeg,quicktime,avi,mov,wmv,flv';
        
        if ($request->hasFile('groom_photo')) {
            $rules['groom_photo'] = 'nullable|image|mimes:' . $imageMimes . '|max:' . $imageMaxSize . '|file';
        }
        
        if ($request->hasFile('bride_photo')) {
            $rules['bride_photo'] = 'nullable|image|mimes:' . $imageMimes . '|max:' . $imageMaxSize . '|file';
        }
        
        if ($request->hasFile('gift_image')) {
            $rules['gift_image'] = 'nullable|image|mimes:' . $imageMimes . '|max:' . $imageMaxSize . '|file';
        }
        
        if ($request->hasFile('gallery_photos')) {
            $rules['gallery_photos.*'] = 'nullable|image|mimes:' . $imageMimes . '|max:' . $galleryImageMaxSize . '|file';
        }
        
        if ($request->hasFile('gallery_videos')) {
            $rules['gallery_videos.*'] = 'nullable|mimetypes:video/' . str_replace(',', ',video/', $videoMimes) . '|max:' . $videoMaxSize . '|file';
        }
        
        // Conditional validation for Akad Nikah
        if ($request->has('akadNikahToggle') && $request->input('akadNikahToggle') == '1') {
            $rules['akad_date'] = 'required|date';
            $rules['akad_time'] = 'required';
            $rules['akad_location'] = 'required|string';
        }
        
        // Conditional validation for Reception
        if ($request->has('resepsiToggle') && $request->input('resepsiToggle') == '1') {
            if ($request->has('receptions') && is_array($request->receptions)) {
                foreach ($request->receptions as $key => $reception) {
                    if (isset($reception['name']) || isset($reception['date']) || isset($reception['location'])) {
                        $rules["receptions.{$key}.name"] = 'required|string';
                        $rules["receptions.{$key}.date"] = 'required|date';
                        $rules["receptions.{$key}.location"] = 'required|string';
                    }
                }
            }
        }
        
        return $request->validate($rules, $messages);
    }

    private function uploadFile(Request $request, $fieldName, $directory)
{
        if ($request->hasFile($fieldName)) {
            try {
                $file = $request->file($fieldName);
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $filename, 'public');
                Log::info("File uploaded: {$fieldName} -> {$path}");
                return $path;
            } catch (\Exception $e) {
                Log::error("Failed to upload {$fieldName}: " . $e->getMessage());
                return null;
            }
        }
        return null;
    }

    private function uploadMultipleFiles($files, $directory)
    {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($directory, $filename, 'public');
            $uploadedFiles[] = $path;
        }
        
        return $uploadedFiles;
    }

    private function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function deleteMultipleFiles($filePaths)
    {
        if (is_array($filePaths)) {
            foreach ($filePaths as $filePath) {
                $this->deleteFile($filePath);
            }
        }
    }
}
