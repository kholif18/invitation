@php
    $gallery = $invitation->getGallery();
    $photos = $gallery['photos'];
    $isPreview = $isPreview ?? false;
    
    // Dummy images for preview
    $dummyImages = [
        asset('assets/templates/classic-elegant/images/dummy/gallery-1.jpg'),
        asset('assets/templates/classic-elegant/images/dummy/gallery-2.jpg'),
        asset('assets/templates/classic-elegant/images/dummy/gallery-3.jpg'),
        asset('assets/templates/classic-elegant/images/dummy/gallery-4.jpg'),
    ];
    
    // Gunakan dummy images untuk preview atau real images
    if ($isPreview && $photos->count() == 0) {
        $displayImages = $dummyImages;
    } else {
        $displayImages = $photos->map(function($photo) {
            return asset('storage/' . $photo);
        })->toArray();
    }
@endphp

@if($invitation->has_gallery)
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Gallery</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-images"></i>
                <span></span>
            </div>
            <p class="section-subtitle">
                Momen kebahagiaan kami
            </p>
        </div>
        
        <div class="gallery-grid">
            @foreach($displayImages as $index => $imageUrl)
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 100 }}">
                <img src="{{ $imageUrl }}" alt="Gallery {{ $index + 1 }}" onclick="openLightbox({{ $index }})">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Lightbox Modal -->
        <div class="lightbox-modal" id="lightboxModal">
            <div class="lightbox-content">
                <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
                <img id="lightboxImage" src="">
                <button class="lightbox-prev" onclick="prevImage()">&#10094;</button>
                <button class="lightbox-next" onclick="nextImage()">&#10095;</button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    let currentImageIndex = 0;
    let galleryImages = @json($displayImages);
    
    function openLightbox(index) {
        if (!galleryImages || galleryImages.length === 0) {
            console.log('No images to display');
            return;
        }
        currentImageIndex = index;
        const modal = document.getElementById('lightboxModal');
        const image = document.getElementById('lightboxImage');
        if (modal && image && galleryImages[currentImageIndex]) {
            image.src = galleryImages[currentImageIndex];
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    
    function prevImage() {
        if (!galleryImages || galleryImages.length === 0) return;
        currentImageIndex--;
        if (currentImageIndex < 0) {
            currentImageIndex = galleryImages.length - 1;
        }
        const image = document.getElementById('lightboxImage');
        if (image && galleryImages[currentImageIndex]) {
            image.src = galleryImages[currentImageIndex];
        }
    }
    
    function nextImage() {
        if (!galleryImages || galleryImages.length === 0) return;
        currentImageIndex++;
        if (currentImageIndex >= galleryImages.length) {
            currentImageIndex = 0;
        }
        const image = document.getElementById('lightboxImage');
        if (image && galleryImages[currentImageIndex]) {
            image.src = galleryImages[currentImageIndex];
        }
    }
    
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightboxModal');
        if (modal && modal.style.display === 'flex') {
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });
    
    // Close lightbox when clicking outside the image
    document.getElementById('lightboxModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
</script>
@endpush
@endif