@if($invitation->maps && count($invitation->maps) > 0)
<section class="map-section" id="map">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Lokasi Acara</h2>
            <div class="section-divider">
                <span></span>
                <i class="fas fa-map-marked-alt"></i>
                <span></span>
            </div>
            <p class="section-subtitle">
                Temukan lokasi acara dengan mudah melalui peta di bawah ini
            </p>
        </div>
        
        <div class="map-container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                @foreach($invitation->getMaps() as $index => $mapLink)
                <div class="col-12 mb-4">
                    <div class="map-card">
                        <div class="map-header">
                            <h4>Lokasi {{ $index + 1 }}</h4>
                            @if($invitation->has_reception && isset($invitation->receptions[$index]))
                                <p class="map-location-name">{{ $invitation->receptions[$index]['name'] ?? 'Lokasi Acara' }}</p>
                            @endif
                        </div>
                        <div class="map-embed">
                            @if(strpos($mapLink, 'iframe') !== false)
                                {!! $mapLink !!}
                            @elseif(strpos($mapLink, 'maps.google') !== false)
                                <iframe 
                                    src="{{ $mapLink }}"
                                    width="100%" 
                                    height="400" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            @else
                                <div class="map-link">
                                    <a href="{{ $mapLink }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt"></i> Buka Peta di Google Maps
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="map-footer">
                            <button onclick="getDirection({{ $index }})" class="btn btn-outline-primary">
                                <i class="fas fa-directions"></i> Dapatkan Arah
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function getDirection(mapIndex) {
        const maps = @json($invitation->getMaps()->toArray());
        const mapLink = maps[mapIndex];
        
        let address = '';
        if (mapLink.includes('q=')) {
            const match = mapLink.match(/q=([^&]+)/);
            if (match) {
                address = decodeURIComponent(match[1]);
            }
        } else if (mapLink.includes('place/')) {
            const match = mapLink.match(/place\/([^\/]+)/);
            if (match) {
                address = decodeURIComponent(match[1].replace(/\+/g, ' '));
            }
        }
        
        if (address) {
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(address)}`;
            window.open(googleMapsUrl, '_blank');
        } else if (mapLink.includes('http')) {
            window.open(mapLink, '_blank');
        }
    }
</script>
@endpush
@endif