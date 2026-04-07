<section class="hero-section" id="home">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content text-center" data-aos="fade-up" data-aos-duration="1500">
            <div class="ornament-top">
                <i class="fas fa-heart"></i> 
                <i class="fas fa-heart"></i> 
                <i class="fas fa-heart"></i>
            </div>
            <h6 class="hero-subtitle">The Wedding of</h6>
            <h1 class="hero-title">
                {{ $invitation->groom_full_name }}<br>
                <span class="ampersand">&</span><br>
                {{ $invitation->bride_full_name }}
            </h1>
            <div class="hero-date">
                <i class="far fa-calendar-alt me-2"></i>
                @php
                    $weddingDate = $invitation->getWeddingDateAttribute();
                @endphp
                @if($weddingDate)
                    {{ $weddingDate->format('l, d F Y') }}
                @endif
            </div>
            <div class="hero-buttons mt-4">
                <button class="btn btn-primary btn-open-invitation" onclick="openInvitation()">
                    Buka Undangan <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
            <div class="ornament-bottom">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function openInvitation() {
        document.querySelector('.hero-section').classList.add('fade-out');
        setTimeout(() => {
            document.querySelector('.hero-section').style.display = 'none';
            document.body.classList.add('invitation-opened');
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }, 500);
    }
    
    // Auto hide loading screen
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loading = document.getElementById('loading');
            if (loading) {
                loading.style.display = 'none';
            }
        }, 1000);
    });
</script>
@endpush