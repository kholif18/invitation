<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(isset($isPreview) && $isPreview) Preview: @endif{{ $invitation->groom_full_name ?? 'Template' }} & {{ $invitation->bride_full_name ?? 'Preview' }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/templates/classic-elegant/css/style.css') }}">
    
    @php
        $settings = $invitation->getTemplateSettings() ?? [];
        $primaryColor = $settings['primary_color'] ?? '#8B4513';
        $secondaryColor = $settings['secondary_color'] ?? '#DAA520';
        $accentColor = $settings['accent_color'] ?? '#FDF5E6';
        $textColor = $settings['text_color'] ?? '#333333';
        $primaryFont = $settings['primary_font'] ?? 'Poppins';
        $titleFont = $settings['title_font'] ?? 'Playfair Display';
        
        $isPreview = $isPreview ?? false;
        $enableMusic = isset($settings['enable_music']) && $settings['enable_music'] && !$isPreview;
    @endphp
</head>
<body>
    @if($isPreview)
    <div class="preview-banner">
        <i class="fas fa-eye"></i> PREVIEW MODE - This is a template preview with sample data. Actual content will be replaced with your invitation data.
    </div>
    @endif
    
    <!-- Loading Screen -->
    <div id="loading">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
        <p class="mt-3">Memuat Undangan...</p>
    </div>
    
    <!-- Background Music -->
    @if($enableMusic)
    <div class="music-player" id="musicPlayer">
        <button class="music-toggle" id="musicToggle">
            <i class="fas fa-music"></i>
        </button>
        <div class="volume-slider-container" id="volumeSliderContainer">
            <input type="range" class="volume-slider" id="volumeSlider" min="0" max="100" value="50">
        </div>
        <audio id="bgMusic" loop preload="auto">
            <source src="{{ asset('assets/templates/classic-elegant/audio/background-music.mp3') }}" type="audio/mpeg">
        </audio>
    </div>
    @endif
    
    <!-- Main Content -->
    <div class="invitation-wrapper">
        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/templates/classic-elegant/js/script.js') }}"></script>
    
    <script>
        window.invitationData = {
            isPreview: {{ $isPreview ? 'true' : 'false' }},
            slug: '{{ $invitation->slug ?? "" }}',
            guestCode: '{{ $guest->invitation_code ?? "" }}',
            csrfToken: '{{ csrf_token() }}',
            wishUrl: '{{ !$isPreview && isset($invitation->slug) && $invitation->slug ? route("invitation.wish", $invitation->slug) : "#" }}',
            enableMusic: {{ $enableMusic ? 'true' : 'false' }}
        };
        
        @if($isPreview)
        console.log('Preview mode active - Wish form disabled');
        @endif
    </script>
    
    <script>
        // Music Player dengan Autoplay dan Mute
        document.addEventListener('DOMContentLoaded', function() {
            const musicToggle = document.getElementById('musicToggle');
            const bgMusic = document.getElementById('bgMusic');
            const volumeSlider = document.getElementById('volumeSlider');
            const volumeSliderContainer = document.getElementById('volumeSliderContainer');
            let isPlaying = false;
            let isMuted = false;
            
            if (musicToggle && bgMusic && window.invitationData.enableMusic) {
                // Set initial volume
                bgMusic.volume = 0.5;
                if (volumeSlider) volumeSlider.value = 50;
                
                // Try to autoplay
                const attemptAutoplay = function() {
                    bgMusic.play().then(() => {
                        isPlaying = true;
                        musicToggle.classList.add('playing');
                        musicToggle.innerHTML = '<i class="fas fa-pause"></i>';
                        console.log('Music autoplay started');
                    }).catch(e => {
                        console.log('Autoplay prevented by browser:', e);
                        // Show play button instead
                        musicToggle.classList.remove('playing');
                        musicToggle.innerHTML = '<i class="fas fa-play"></i>';
                        isPlaying = false;
                    });
                };
                
                // Attempt autoplay immediately
                attemptAutoplay();
                
                // Toggle play/pause on button click
                musicToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (isPlaying) {
                        bgMusic.pause();
                        musicToggle.classList.remove('playing');
                        musicToggle.innerHTML = '<i class="fas fa-play"></i>';
                        isPlaying = false;
                    } else {
                        bgMusic.play().then(() => {
                            musicToggle.classList.add('playing');
                            musicToggle.innerHTML = '<i class="fas fa-pause"></i>';
                            isPlaying = true;
                        }).catch(e => console.log('Play failed:', e));
                    }
                });
                
                // Right click untuk mute/unmute (alternative)
                musicToggle.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    if (isMuted) {
                        bgMusic.volume = bgMusic._previousVolume || 0.5;
                        musicToggle.classList.remove('muted');
                        isMuted = false;
                    } else {
                        bgMusic._previousVolume = bgMusic.volume;
                        bgMusic.volume = 0;
                        musicToggle.classList.add('muted');
                        isMuted = true;
                    }
                });
                
                // Volume control
                if (volumeSlider) {
                    // Show/hide volume slider on hover
                    musicToggle.addEventListener('mouseenter', function() {
                        if (volumeSliderContainer) {
                            volumeSliderContainer.classList.add('show');
                        }
                    });
                    
                    musicToggle.addEventListener('mouseleave', function() {
                        setTimeout(() => {
                            if (volumeSliderContainer && !volumeSliderContainer.matches(':hover')) {
                                volumeSliderContainer.classList.remove('show');
                            }
                        }, 300);
                    });
                    
                    volumeSliderContainer.addEventListener('mouseenter', function() {
                        this.classList.add('show');
                    });
                    
                    volumeSliderContainer.addEventListener('mouseleave', function() {
                        this.classList.remove('show');
                    });
                    
                    volumeSlider.addEventListener('input', function() {
                        const volume = this.value / 100;
                        bgMusic.volume = volume;
                        if (volume === 0) {
                            musicToggle.classList.add('muted');
                            isMuted = true;
                        } else {
                            musicToggle.classList.remove('muted');
                            isMuted = false;
                        }
                    });
                }
                
                // Handle page visibility (pause when tab is hidden)
                document.addEventListener('visibilitychange', function() {
                    if (document.hidden && isPlaying) {
                        bgMusic.pause();
                    } else if (!document.hidden && isPlaying) {
                        bgMusic.play().catch(e => console.log('Resume failed:', e));
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>