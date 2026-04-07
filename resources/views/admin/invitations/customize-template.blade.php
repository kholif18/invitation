{{-- resources/views/admin/invitations/customize-template.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Customize Template - ' . $invitation->groom_full_name . ' & ' . $invitation->bride_full_name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.invitations.index') }}" class="text-muted text-hover-primary">All Invitations</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.invitations.show', $invitation) }}" class="text-muted text-hover-primary">
        {{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Customize Template</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.invitations.update-template-settings', $invitation) }}" method="POST" enctype="multipart/form-data" id="customizeForm">
            @csrf
            @method('PUT')
            
            <!-- Background Music Settings -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Background Music</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch form-check-custom form-check-solid mb-4">
                        <input class="form-check-input" type="checkbox" name="enable_music" value="1" id="enableMusic" {{ ($currentSettings['enable_music'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="enableMusic">
                            Enable Background Music
                        </label>
                    </div>
                    
                    <div id="musicSettings" style="{{ ($currentSettings['enable_music'] ?? true) ? 'display: block;' : 'display: none;' }}">
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Upload Custom Music (MP3)</label>
                            @if(isset($currentSettings['music_path']))
                                <div class="alert alert-info mb-2">
                                    <i class="bi bi-music-note"></i> Current music: 
                                    <audio controls class="mt-2 w-100">
                                        <source src="{{ asset('storage/' . $currentSettings['music_path']) }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            @endif
                            <input type="file" class="form-control" name="custom_music" accept="audio/mpeg,audio/mp3">
                            <div class="form-text">Max 10MB. Leave empty to keep current music.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Wedding Wording -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Wedding Wording</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Opening Greeting</label>
                        <textarea class="form-control" name="opening_greeting" rows="3"><?php echo htmlspecialchars($currentSettings['opening_greeting'] ?? 'Assalamualaikum Warahmatullahi Wabarakatuh'); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Invitation Text</label>
                        <textarea class="form-control" name="invitation_text" rows="4"><?php echo htmlspecialchars($currentSettings['invitation_text'] ?? 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:'); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Footer Message</label>
                        <textarea class="form-control" name="footer_message" rows="3"><?php echo htmlspecialchars($currentSettings['footer_message'] ?? 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu.'); ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Closing Greeting</label>
                        <textarea class="form-control" name="closing_greeting" rows="2"><?php echo htmlspecialchars($currentSettings['closing_greeting'] ?? 'Wassalamualaikum Warahmatullahi Wabarakatuh'); ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Color Scheme -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Color Scheme</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Primary Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" name="primary_color" value="{{ $currentSettings['primary_color'] ?? '#8B4513' }}" style="width: 60px;">
                                <input type="text" class="form-control" value="{{ $currentSettings['primary_color'] ?? '#8B4513' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Secondary Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" name="secondary_color" value="{{ $currentSettings['secondary_color'] ?? '#D2691E' }}" style="width: 60px;">
                                <input type="text" class="form-control" value="{{ $currentSettings['secondary_color'] ?? '#D2691E' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Accent Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" name="accent_color" value="{{ $currentSettings['accent_color'] ?? '#F5DEB3' }}" style="width: 60px;">
                                <input type="text" class="form-control" value="{{ $currentSettings['accent_color'] ?? '#F5DEB3' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Text Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" name="text_color" value="{{ $currentSettings['text_color'] ?? '#333333' }}" style="width: 60px;">
                                <input type="text" class="form-control" value="{{ $currentSettings['text_color'] ?? '#333333' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Font Settings -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Font Settings</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Primary Font</label>
                            <select class="form-control" name="primary_font">
                                <option value="Poppins" {{ ($currentSettings['primary_font'] ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                <option value="Montserrat" {{ ($currentSettings['primary_font'] ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                <option value="Open Sans" {{ ($currentSettings['primary_font'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Roboto" {{ ($currentSettings['primary_font'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Lato" {{ ($currentSettings['primary_font'] ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Title Font</label>
                            <select class="form-control" name="title_font">
                                <option value="Playfair Display" {{ ($currentSettings['title_font'] ?? 'Playfair Display') == 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                                <option value="Cormorant Garamond" {{ ($currentSettings['title_font'] ?? '') == 'Cormorant Garamond' ? 'selected' : '' }}>Cormorant Garamond</option>
                                <option value="Great Vibes" {{ ($currentSettings['title_font'] ?? '') == 'Great Vibes' ? 'selected' : '' }}>Great Vibes</option>
                                <option value="Sacramento" {{ ($currentSettings['title_font'] ?? '') == 'Sacramento' ? 'selected' : '' }}>Sacramento</option>
                                <option value="Alex Brush" {{ ($currentSettings['title_font'] ?? '') == 'Alex Brush' ? 'selected' : '' }}>Alex Brush</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Layout Settings -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Layout & Animation</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Layout Style</label>
                            <select class="form-control" name="layout_style">
                                <option value="default" {{ ($currentSettings['layout_style'] ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                <option value="modern" {{ ($currentSettings['layout_style'] ?? '') == 'modern' ? 'selected' : '' }}>Modern</option>
                                <option value="simple" {{ ($currentSettings['layout_style'] ?? '') == 'simple' ? 'selected' : '' }}>Simple</option>
                                <option value="elegant" {{ ($currentSettings['layout_style'] ?? '') == 'elegant' ? 'selected' : '' }}>Elegant</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Animation Effect</label>
                            <select class="form-control" name="animation">
                                <option value="fade" {{ ($currentSettings['animation'] ?? 'fade') == 'fade' ? 'selected' : '' }}>Fade</option>
                                <option value="slide" {{ ($currentSettings['animation'] ?? '') == 'slide' ? 'selected' : '' }}>Slide</option>
                                <option value="zoom" {{ ($currentSettings['animation'] ?? '') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                <option value="none" {{ ($currentSettings['animation'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gallery Settings -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Gallery Settings</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Gallery Layout</label>
                            <select class="form-control" name="gallery_layout">
                                <option value="grid" {{ ($currentSettings['gallery_layout'] ?? 'grid') == 'grid' ? 'selected' : '' }}>Grid</option>
                                <option value="masonry" {{ ($currentSettings['gallery_layout'] ?? '') == 'masonry' ? 'selected' : '' }}>Masonry</option>
                                <option value="carousel" {{ ($currentSettings['gallery_layout'] ?? '') == 'carousel' ? 'selected' : '' }}>Carousel</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold mb-2">Items Per Row</label>
                            <select class="form-control" name="gallery_items_per_row">
                                <option value="3" {{ ($currentSettings['gallery_items_per_row'] ?? 3) == 3 ? 'selected' : '' }}>3 Items</option>
                                <option value="4" {{ ($currentSettings['gallery_items_per_row'] ?? '') == 4 ? 'selected' : '' }}>4 Items</option>
                                <option value="6" {{ ($currentSettings['gallery_items_per_row'] ?? '') == 6 ? 'selected' : '' }}>6 Items</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Features -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Additional Features</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch form-check-custom form-check-solid mb-4">
                        <input class="form-check-input" type="checkbox" name="show_countdown" value="1" id="showCountdown" {{ ($currentSettings['show_countdown'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="showCountdown">
                            Show Countdown Timer
                        </label>
                    </div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid mb-4">
                        <input class="form-check-input" type="checkbox" name="show_gift_section" value="1" id="showGiftSection" {{ ($currentSettings['show_gift_section'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="showGiftSection">
                            Show Gift Section
                        </label>
                    </div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid mb-4">
                        <input class="form-check-input" type="checkbox" name="show_comment_section" value="1" id="showCommentSection" {{ ($currentSettings['show_comment_section'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="showCommentSection">
                            Show Comment Section
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-3 mt-6 mb-6">
                <a href="{{ route('admin.invitations.show', $invitation) }}" class="btn btn-light">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Template Settings
                </button>
            </div>
        </form>
    </div>
    
    <!-- Sidebar Preview -->
    <div class="col-md-4">
        <div class="card mb-6 sticky-top" style="top: 20px;">
            <div class="card-header">
                <h3 class="card-title">Live Preview</h3>
            </div>
            <div class="card-body p-0">
                <div class="preview-box p-3" style="background: var(--primary-light, #f5f5f5);">
                    <div class="text-center mb-3">
                        <div class="preview-color-preview mb-2">
                            <div class="d-flex gap-2 justify-content-center">
                                <div class="rounded-circle" style="width: 30px; height: 30px; background: {{ $currentSettings['primary_color'] ?? '#8B4513' }};"></div>
                                <div class="rounded-circle" style="width: 30px; height: 30px; background: {{ $currentSettings['secondary_color'] ?? '#D2691E' }};"></div>
                                <div class="rounded-circle" style="width: 30px; height: 30px; background: {{ $currentSettings['accent_color'] ?? '#F5DEB3' }};"></div>
                            </div>
                        </div>
                        <h4 style="font-family: '{{ $currentSettings['title_font'] ?? 'Playfair Display' }}';">Sample Title</h4>
                        <p style="font-family: '{{ $currentSettings['primary_font'] ?? 'Poppins' }}'; color: {{ $currentSettings['text_color'] ?? '#333' }};">
                            Sample text preview with selected font and color settings.
                        </p>
                        <button class="btn btn-sm" style="background: {{ $currentSettings['primary_color'] ?? '#8B4513' }}; color: white;">
                            Sample Button
                        </button>
                    </div>
                </div>
                <div class="p-3 border-top">
                    <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="btn btn-light-primary w-100">
                        <i class="bi bi-eye"></i> View Full Preview
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle music settings
    document.getElementById('enableMusic').addEventListener('change', function() {
        const musicSettings = document.getElementById('musicSettings');
        musicSettings.style.display = this.checked ? 'block' : 'none';
    });
    
    // Live preview color update
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(input => {
        input.addEventListener('change', function() {
            const value = this.value;
            const textInput = this.closest('.d-flex').querySelector('input[type="text"]');
            if (textInput) textInput.value = value;
            
            // Update preview
            if (this.name === 'primary_color') {
                document.querySelector('.preview-color-preview .rounded-circle:first-child').style.background = value;
                document.querySelector('.preview-box .btn').style.background = value;
            } else if (this.name === 'secondary_color') {
                document.querySelector('.preview-color-preview .rounded-circle:nth-child(2)').style.background = value;
            } else if (this.name === 'accent_color') {
                document.querySelector('.preview-color-preview .rounded-circle:nth-child(3)').style.background = value;
            } else if (this.name === 'text_color') {
                document.querySelector('.preview-box p').style.color = value;
            }
        });
    });
    
    // Live preview font update
    const fontInputs = document.querySelectorAll('select[name="primary_font"], select[name="title_font"]');
    fontInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.name === 'primary_font') {
                document.querySelector('.preview-box p').style.fontFamily = this.value;
            } else if (this.name === 'title_font') {
                document.querySelector('.preview-box h4').style.fontFamily = this.value;
            }
        });
    });
</script>
@endpush
@endsection