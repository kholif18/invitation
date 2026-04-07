<footer class="footer-section">
    <div class="container">
        <div class="footer-content text-center">
            <div class="footer-ornament">
                <i class="fas fa-heart"></i>
                <i class="fas fa-heart"></i>
                <i class="fas fa-heart"></i>
            </div>
            <p class="footer-text">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami,<br>
                apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu.
            </p>
            <p class="footer-thanks">
                Terima kasih atas doa dan restunya.<br>
                Wassalamualaikum Warahmatullahi Wabarakatuh
            </p>
            <div class="footer-copyright">
                <p>&copy; {{ date('Y') }} {{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <div class="fab-container">
        <button class="fab" id="fabToggle">
            <i class="fas fa-share-alt"></i>
        </button>
        <div class="fab-options">
            <button class="fab-option" onclick="shareToWhatsApp()">
                <i class="fab fa-whatsapp"></i>
            </button>
            <button class="fab-option" onclick="shareToFacebook()">
                <i class="fab fa-facebook-f"></i>
            </button>
            <button class="fab-option" onclick="copyInvitationLink()">
                <i class="fas fa-link"></i>
            </button>
            <button class="fab-option" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </div>
</footer>

@push('scripts')
<script>
    // FAB Toggle
    const fabToggle = document.getElementById('fabToggle');
    if (fabToggle) {
        fabToggle.addEventListener('click', function() {
            const fabOptions = document.querySelector('.fab-options');
            if (fabOptions) {
                fabOptions.classList.toggle('show');
            }
        });
    }
    
    function shareToWhatsApp() {
        const url = window.location.href;
        const title = document.querySelector('.hero-title');
        const text = title ? `Undangan Pernikahan ${title.innerText.replace(/\n/g, ' ')}\n\n${url}` : `Undangan Pernikahan\n\n${url}`;
        window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
    }
    
    function shareToFacebook() {
        const url = window.location.href;
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
    }
    
    function copyInvitationLink() {
        navigator.clipboard.writeText(window.location.href);
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Link undangan telah disalin',
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // Close FAB when clicking outside
    document.addEventListener('click', function(event) {
        const fabContainer = document.querySelector('.fab-container');
        const fabOptions = document.querySelector('.fab-options');
        if (fabContainer && fabOptions && !fabContainer.contains(event.target)) {
            fabOptions.classList.remove('show');
        }
    });
    
    // Scroll to top button
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollTopBtn.classList.add('scroll-top-btn');
    scrollTopBtn.style.cssText = `
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        border: none;
        cursor: pointer;
        display: none;
        z-index: 999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    `;
    
    document.body.appendChild(scrollTopBtn);
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollTopBtn.style.display = 'block';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    });
    
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endpush