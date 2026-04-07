<section class="event-section" id="event">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Save The Date</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-calendar-alt"></i>
                <span></span>
            </div>
            <p class="section-subtitle">
                Merupakan suatu kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.
            </p>
        </div>
        
        @if($invitation->has_akad)
        <div class="event-card" data-aos="fade-up" data-aos-delay="100">
            <div class="event-icon">
                <i class="fas fa-ring"></i>
            </div>
            <div class="event-content">
                <h3>Akad Nikah</h3>
                <div class="event-datetime">
                    <i class="far fa-calendar-alt me-2"></i>
                    {{ \Carbon\Carbon::parse($invitation->akad_date)->format('l, d F Y') }}
                    <i class="far fa-clock ms-3 me-2"></i>
                    {{ \Carbon\Carbon::parse($invitation->akad_time)->format('H:i') }} WIB
                </div>
                <div class="event-location">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ $invitation->akad_location }}
                </div>
            </div>
        </div>
        @endif
        
        @if($invitation->has_reception && $invitation->receptions)
            @foreach($invitation->getReceptionDates() as $index => $reception)
            <div class="event-card" data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 100) }}">
                <div class="event-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="event-content">
                    <h3>{{ $reception['name'] }}</h3>
                    <div class="event-datetime">
                        <i class="far fa-calendar-alt me-2"></i>
                        {{ \Carbon\Carbon::parse($reception['date'])->format('l, d F Y') }}
                    </div>
                    <div class="event-location">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $reception['location'] }}
                    </div>
                </div>
            </div>
            @endforeach
        @endif
        
        @if(isset($settings['show_countdown']) && $settings['show_countdown'])
        <div class="countdown-timer" data-aos="fade-up" data-aos-delay="300">
            <h4>Menuju Hari Bahagia</h4>
            <div class="countdown">
                <div class="countdown-item">
                    <span id="days">00</span>
                    <p>Hari</p>
                </div>
                <div class="countdown-item">
                    <span id="hours">00</span>
                    <p>Jam</p>
                </div>
                <div class="countdown-item">
                    <span id="minutes">00</span>
                    <p>Menit</p>
                </div>
                <div class="countdown-item">
                    <span id="seconds">00</span>
                    <p>Detik</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    // Countdown timer
    @php
        $weddingDate = $invitation->getWeddingDateAttribute();
    @endphp
    
    @if($weddingDate && isset($settings['show_countdown']) && $settings['show_countdown'])
    function updateCountdown() {
        const weddingDate = new Date('{{ $weddingDate->format('Y-m-d') }}').getTime();
        const now = new Date().getTime();
        const distance = weddingDate - now;
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const daysEl = document.getElementById('days');
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        
        if (daysEl) daysEl.innerHTML = String(days).padStart(2, '0');
        if (hoursEl) hoursEl.innerHTML = String(hours).padStart(2, '0');
        if (minutesEl) minutesEl.innerHTML = String(minutes).padStart(2, '0');
        if (secondsEl) secondsEl.innerHTML = String(seconds).padStart(2, '0');
        
        if (distance < 0) {
            const countdownEl = document.querySelector('.countdown-timer');
            if (countdownEl) countdownEl.style.display = 'none';
        }
    }
    
    setInterval(updateCountdown, 1000);
    updateCountdown();
    @endif
</script>
@endpush