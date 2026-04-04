<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::withCount(['guests', 'wishes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
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
        $validated = $this->validateInvitation($request);
        
        // Verify template exists and is active
        $template = Template::where('id', $validated['template_id'])
            ->where('is_active', true)
            ->first();
        
        if (!$template) {
            return back()->with('error', 'Invalid or inactive template selected.');
        }
        
        DB::beginTransaction();
        
        try {
            // Handle file uploads
            $validated['groom_photo'] = $this->uploadFile($request, 'groom_photo', 'invitations/groom');
            $validated['bride_photo'] = $this->uploadFile($request, 'bride_photo', 'invitations/bride');
            $validated['gift_image'] = $this->uploadFile($request, 'gift_image', 'invitations/gift');
            
            // Handle gallery uploads
            if ($request->hasFile('gallery_photos')) {
                $validated['gallery_photos'] = $this->uploadMultipleFiles($request->file('gallery_photos'), 'invitations/gallery/photos');
            }
            
            if ($request->hasFile('gallery_videos')) {
                $validated['gallery_videos'] = $this->uploadMultipleFiles($request->file('gallery_videos'), 'invitations/gallery/videos');
            }
            
            // Handle song theme
            if ($request->hasFile('song_theme')) {
                $validated['song_theme'] = $this->uploadFile($request, 'song_theme', 'invitations/songs');
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
            
            // Create invitation with template_id
            $validated['template_id'] = $template->id;
            $validated['template_slug'] = $template->slug;
            
            $invitation = Invitation::create($validated);
            
            DB::commit();
            
            $message = $validated['status'] === 'published' 
                ? 'Wedding invitation created successfully! You can now add guests.' 
                : 'Wedding invitation saved as draft! You can add guests later.';
            
            return redirect()->route('admin.invitations.show', $invitation)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create invitation: ' . $e->getMessage());
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
        return view('admin.invitations.edit', compact('invitation'));
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
            $this->deleteFile($invitation->song_theme);
            
            // Delete all guests and wishes (cascade will handle)
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

    private function validateInvitation(Request $request, $id = null)
    {
        $rules = [
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
            'groom_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'bride_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'gift_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'gallery_photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'gallery_videos.*' => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:204800',
            'song_theme' => 'nullable|mimetypes:audio/mpeg,audio/mp3|max:10240',
            'status' => 'required|in:draft,published',
            'template_id' => 'nullable|string|max:255',
        ];
        
        // Add conditional validation for akad nikah
        if ($request->has('akadNikahToggle')) {
            $rules['akad_date'] = 'required|date';
            $rules['akad_time'] = 'required';
            $rules['akad_location'] = 'required|string';
        }
        
        // Add conditional validation for reception
        if ($request->has('resepsiToggle') && $request->has('receptions')) {
            foreach ($request->receptions as $key => $reception) {
                $rules["receptions.{$key}.name"] = 'required|string';
                $rules["receptions.{$key}.date"] = 'required|date';
                $rules["receptions.{$key}.location"] = 'required|string';
            }
        }
        
        return $request->validate($rules);
    }

    private function uploadFile(Request $request, $fieldName, $directory)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($directory, $filename, 'public');
            return $path;
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
