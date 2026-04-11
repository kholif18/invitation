{{-- resources/views/templates/classic-elegant/sections/couple.blade.php --}}
<section class="couple-section" id="couple">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Assalamualaikum Warahmatullahi Wabarakatuh</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-heart"></i>
                <span></span>
            </div>
            <p class="section-subtitle">
                Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-5" data-aos="fade-right" data-aos-delay="200">
                <div class="couple-card text-center">
                    <div class="couple-image">
                        @if($invitation->groom_photo)
                            <img src="{{ asset('storage/' . $invitation->groom_photo) }}" alt="{{ $invitation->groom_full_name }}">
                        @elseif(isset($isPreview) && $isPreview)
                            {{-- Preview mode: tampilkan gambar dummy dari assets template --}}
                            <img src="{{ asset('assets/templates/classic-elegant/images/groom-placeholder.jpg') }}" alt="Groom Preview">
                        @else
                            <div class="placeholder-image">
                                <i class="fas fa-user-circle fa-5x"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="couple-name">{{ $invitation->groom_full_name }}</h3>
                    <p class="couple-nickname">({{ $invitation->groom_nickname }})</p>
                    <p class="couple-parents">
                        Putra dari Bapak {{ $invitation->groom_father_name }}<br>
                        & Ibu {{ $invitation->groom_mother_name }}
                    </p>
                    <div class="couple-address">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ $invitation->groom_address }}
                    </div>
                </div>
            </div>
            
            <div class="col-md-2 text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="and-icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
            
            <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
                <div class="couple-card text-center">
                    <div class="couple-image">
                        @if($invitation->bride_photo)
                            <img src="{{ asset('storage/' . $invitation->bride_photo) }}" alt="{{ $invitation->bride_full_name }}">
                        @elseif(isset($isPreview) && $isPreview)
                            {{-- Preview mode: tampilkan gambar dummy dari assets template --}}
                            <img src="{{ asset('assets/templates/classic-elegant/images/bride-placeholder.jpg') }}" alt="Bride Preview">
                        @else
                            <div class="placeholder-image">
                                <i class="fas fa-user-circle fa-5x"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="couple-name">{{ $invitation->bride_full_name }}</h3>
                    <p class="couple-nickname">({{ $invitation->bride_nickname }})</p>
                    <p class="couple-parents">
                        Putri dari Bapak {{ $invitation->bride_father_name }}<br>
                        & Ibu {{ $invitation->bride_mother_name }}
                    </p>
                    <div class="couple-address">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ $invitation->bride_address }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>