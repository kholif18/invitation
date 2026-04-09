{{-- resources/views/invitations/fallback.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Undangan Pernikahan - {{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fef8f0;
        }
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #8B4513, #5C3317);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero-title {
            font-size: 48px;
            font-weight: bold;
            margin: 20px 0;
            font-family: 'Georgia', serif;
        }
        .hero-subtitle {
            font-size: 18px;
            letter-spacing: 3px;
        }
        .btn-open {
            background: #DAA520;
            color: #8B4513;
            padding: 12px 30px;
            border-radius: 50px;
            border: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .couple-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .section {
            padding: 80px 0;
        }
        .section-title {
            font-size: 36px;
            color: #8B4513;
            margin-bottom: 20px;
            font-family: 'Georgia', serif;
        }
        .section-divider {
            width: 60px;
            height: 3px;
            background: #DAA520;
            margin: 20px auto;
        }
        .event-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .event-icon {
            font-size: 30px;
            color: #DAA520;
            margin-right: 15px;
        }
        .footer {
            background: #8B4513;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 32px; }
            .section-title { font-size: 28px; }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div id="loading" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; display: flex; justify-content: center; align-items: center; z-index: 9999;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Memuat undangan...</p>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="hero-content">
                <p class="hero-subtitle">The Wedding of</p>
                <h1 class="hero-title">
                    {{ $invitation->groom_full_name }}<br>
                    &<br>
                    {{ $invitation->bride_full_name }}
                </h1>
                @php
                    $weddingDate = $invitation->getWeddingDateAttribute();
                @endphp
                @if($weddingDate)
                    <p class="mt-3">{{ $weddingDate->format('l, d F Y') }}</p>
                @endif
                <button class="btn-open" onclick="openInvitation()">
                    Buka Undangan <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div id="mainContent" style="display: none;">
        <!-- Couple Section -->
        <div class="container section">
            <h2 class="section-title text-center">Assalamualaikum Wr. Wb.</h2>
            <div class="section-divider"></div>
            <p class="text-center mb-5">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:</p>
            
            <div class="row">
                <div class="col-md-5">
                    <div class="couple-card">
                        @if($invitation->groom_photo)
                            <img src="{{ asset('storage/' . $invitation->groom_photo) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h3>{{ $invitation->groom_full_name }}</h3>
                        <p>({{ $invitation->groom_nickname }})</p>
                        <p>Putra dari Bapak {{ $invitation->groom_father_name }}<br>& Ibu {{ $invitation->groom_mother_name }}</p>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <i class="fas fa-heart fa-3x text-danger" style="margin-top: 80px;"></i>
                </div>
                <div class="col-md-5">
                    <div class="couple-card">
                        @if($invitation->bride_photo)
                            <img src="{{ asset('storage/' . $invitation->bride_photo) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h3>{{ $invitation->bride_full_name }}</h3>
                        <p>({{ $invitation->bride_nickname }})</p>
                        <p>Putri dari Bapak {{ $invitation->bride_father_name }}<br>& Ibu {{ $invitation->bride_mother_name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Section -->
        <div class="container section" style="background: #f5f0e8;">
            <h2 class="section-title text-center">Save The Date</h2>
            <div class="section-divider"></div>
            
            @if($invitation->has_akad)
            <div class="event-card d-flex align-items-center">
                <div class="event-icon">
                    <i class="fas fa-ring"></i>
                </div>
                <div>
                    <h4>Akad Nikah</h4>
                    <p>{{ \Carbon\Carbon::parse($invitation->akad_date)->format('l, d F Y') }} - {{ \Carbon\Carbon::parse($invitation->akad_time)->format('H:i') }} WIB</p>
                    <p>{{ $invitation->akad_location }}</p>
                </div>
            </div>
            @endif
            
            @if($invitation->has_reception && $invitation->receptions)
                @foreach($invitation->getReceptionDates() as $reception)
                <div class="event-card d-flex align-items-center">
                    <div class="event-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <h4>{{ $reception['name'] }}</h4>
                        <p>{{ \Carbon\Carbon::parse($reception['date'])->format('l, d F Y') }}</p>
                        <p>{{ $reception['location'] }}</p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="container">
                <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami,<br>apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu.</p>
                <p class="mt-3">Wassalamualaikum Warahmatullahi Wabarakatuh</p>
                <hr class="my-4" style="background: rgba(255,255,255,0.3);">
                <p>&copy; {{ date('Y') }} {{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Hide loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loading = document.getElementById('loading');
                if (loading) loading.style.display = 'none';
            }, 500);
        });
        
        function openInvitation() {
            const hero = document.getElementById('hero');
            const main = document.getElementById('mainContent');
            
            hero.style.transition = 'opacity 0.5s';
            hero.style.opacity = '0';
            
            setTimeout(() => {
                hero.style.display = 'none';
                main.style.display = 'block';
                document.body.style.background = '#fef8f0';
                
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 1000,
                        once: true
                    });
                }
            }, 500);
        }
        
        // AOS initialization
        if (typeof AOS !== 'undefined') {
            AOS.init();
        }
    </script>
</body>
</html>