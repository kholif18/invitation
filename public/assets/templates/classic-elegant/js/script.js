// Initialize AOS
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }

    // Background Music Controller
    const musicToggle = document.getElementById('musicToggle');
    const bgMusic = document.getElementById('bgMusic');
    let isPlaying = false;

    if (musicToggle && bgMusic) {
        musicToggle.addEventListener('click', function () {
            if (isPlaying) {
                bgMusic.pause();
                musicToggle.classList.remove('playing');
                musicToggle.innerHTML = '<i class="fas fa-music"></i>';
            } else {
                bgMusic.play().catch(e => console.log('Audio play failed:', e));
                musicToggle.classList.add('playing');
                musicToggle.innerHTML = '<i class="fas fa-pause"></i>';
            }
            isPlaying = !isPlaying;
        });

        // Auto play after user interaction
        const playAudio = function () {
            if (!isPlaying && bgMusic) {
                bgMusic.play().catch(e => console.log('Audio play failed:', e));
                if (musicToggle) {
                    musicToggle.classList.add('playing');
                    musicToggle.innerHTML = '<i class="fas fa-pause"></i>';
                }
                isPlaying = true;
            }
            document.removeEventListener('click', playAudio);
            document.removeEventListener('touchstart', playAudio);
        };

        document.addEventListener('click', playAudio);
        document.addEventListener('touchstart', playAudio);
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add floating animation to elements
    document.querySelectorAll('.couple-card, .event-card, .bank-card').forEach(el => {
        el.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-10px)';
        });
        el.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });
    });

    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                    }
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Prevent right-click on images
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            return false;
        });
    });

    // Add page view tracking
    if (window.invitationData && window.invitationData.guestCode && window.invitationData.slug) {
        fetch(`/api/track-view/${window.invitationData.slug}/${window.invitationData.guestCode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.invitationData.csrfToken
            }
        }).catch(err => console.log('View tracked'));
    }

    // Detect device and add appropriate class
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (isMobile) {
        document.body.classList.add('mobile-device');
    } else {
        document.body.classList.add('desktop-device');
    }

    // Parallax effect on scroll
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-section');
        if (hero && hero.style.display !== 'none') {
            hero.style.backgroundPositionY = scrolled * 0.5 + 'px';
        }
    });
});