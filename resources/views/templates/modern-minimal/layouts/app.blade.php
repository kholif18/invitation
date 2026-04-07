{{-- resources/views/templates/modern-minimal/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wedding Invitation - {{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/templates/modern-minimal/css/style.css') }}">
    
    @php
        $settings = $invitation->getTemplateSettings();
        $primaryColor = $settings['primary_color'] ?? '#2C3E50';
        $accentColor = $settings['accent_color'] ?? '#E74C3C';
    @endphp
    
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent: {{ $accentColor }};
            --gray-light: #f8f9fa;
        }
    </style>
</head>
<body>
    <div id="loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <p>Loading...</p>
        </div>
    </div>
    
    @if(isset($settings['enable_music']) && $settings['enable_music'])
    <div class="music-control">
        <button id="musicBtn">
            <i class="fas fa-music"></i>
        </button>
        <audio id="bgAudio" loop>
            <source src="{{ asset('assets/templates/modern-minimal/audio/background-music.mp3') }}" type="audio/mpeg">
        </audio>
    </div>
    @endif
    
    <main>
        @yield('content')
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/templates/modern-minimal/js/script.js') }}"></script>
    
    <script>
        window.invitation = {
            slug: '{{ $invitation->slug }}',
            guestCode: '{{ $guest->invitation_code ?? "" }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</body>
</html>