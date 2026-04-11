{{-- resources/views/templates/classic-elegant/sections/gift.blade.php --}}
<section class="gift-section" id="gift">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Wedding Gift</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-gift"></i>
                <span></span>
            </div>
            <p class="section-subtitle">
            </p>
        </div>
        
        <div class="gift-content" data-aos="fade-up" data-aos-delay="100">
            <div class="gift-image text-center mb-4">
                @if($invitation->gift_image)
                    <img src="{{ asset('storage/' . $invitation->gift_image) }}" alt="Wedding Gift" class="img-fluid rounded">
                @elseif(isset($isPreview) && $isPreview)
                    <img src="{{ asset('assets/templates/classic-elegant/images/gift-placeholder.jpg') }}" alt="Gift Preview" class="img-fluid rounded" style="max-height: 200px;">
                @endif
            </div>
            
            <div class="bank-accounts">
                <h4 class="text-center mb-4">Kirim Kado Pernikahan</h4>
                <div class="row justify-content-center">
                    @foreach($invitation->getBankAccounts() as $bank)
                    <div class="col-md-6 mb-3">
                        <div class="bank-card">
                            <div class="bank-name">{{ $bank['bank_name'] }}</div>
                            <div class="account-name">{{ $bank['account_name'] }}</div>
                            <div class="account-number">
                                {{ $bank['account_number'] }}
                                <button class="btn-copy" onclick="copyToClipboard('{{ $bank['account_number'] }}')">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>