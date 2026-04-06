{{-- resources/views/admin/templates/preview-iframe.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $template->name }}</title>
    
    @if($template->css_file && file_exists(public_path($template->css_file)))
        <link rel="stylesheet" href="{{ asset($template->css_file) }}">
    @endif
    
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        
        .preview-notice {
            background: #f0f0f0;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .preview-notice h3 {
            margin: 0 0 10px 0;
            color: #666;
        }
        
        .preview-notice p {
            margin: 0;
            color: #999;
            font-size: 14px;
        }
        
        .template-content {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="preview-notice">
        <h3>Template Preview: {{ $template->name }}</h3>
        <p>This is a preview of how your invitation will look. Actual content will be replaced with your invitation data.</p>
    </div>
    
    <div class="template-content">
        @php
            // Create dummy invitation data for preview
            $invitation = new \App\Models\Invitation();
            $invitation->groom_full_name = 'Bambang Pamungkas';
            $invitation->groom_nickname = 'Bambang';
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
            $invitation->akad_location = 'Masjid Agung, Jakarta';
            
            $invitation->has_reception = true;
            $invitation->receptions = [
                ['name' => 'Wedding Reception', 'date' => now()->addDays(30)->format('Y-m-d'), 'location' => 'Hotel Indonesia, Jakarta']
            ];
            
            $invitation->has_gift = true;
            $invitation->gift_image = null;
            $invitation->bank_accounts = [
                ['bank_name' => 'BCA', 'account_name' => 'Bambang & Siti', 'account_number' => '1234567890']
            ];
            
            $invitation->has_gallery = true;
            $invitation->gallery_photos = [];
            $invitation->gallery_videos = [];
            
            $invitation->is_wish_active = true;
            $invitation->maps = ['https://www.google.com/maps/embed?pb=...'];
            $invitation->template = $template;
            $invitation->template_settings = $template->settings ?? [];
            
            $wishes = collect([
                (object)[
                    'guest_name' => 'Sample Guest',
                    'message' => 'Selamat atas pernikahannya! Semoga menjadi keluarga sakinah mawaddah warahmah.',
                    'attendance' => 'yes',
                    'attendance_count' => 2,
                    'created_at' => now()
                ]
            ]);
            
            $guest = null;
        @endphp
        
        @if($template->blade_file && view()->exists($template->blade_file))
            @include($template->blade_file)
        @else
            <div style="text-align: center; padding: 100px 20px;">
                <i class="bi bi-exclamation-triangle" style="font-size: 64px; color: #ffc107;"></i>
                <h3>Template Preview Unavailable</h3>
                <p>The template files could not be loaded. Please check the template structure.</p>
                <p>Expected path: <code>{{ $template->blade_file }}</code></p>
            </div>
        @endif
    </div>
</body>
</html>