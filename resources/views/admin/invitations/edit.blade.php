{{-- resources/views/admin/invitations/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Invitation - ' . $invitation->groom_full_name . ' & ' . $invitation->bride_full_name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.invitations.index') }}" class="text-muted text-hover-primary">
        All Invitations
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Edit Wedding Invitation</li>
@endsection

@section('content')
@if($errors->any())
<div class="alert alert-danger mb-6">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
        <div>
            <strong>Terjadi kesalahan!</strong> Silakan periksa kembali form di bawah ini.
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<form id="invitationForm" method="POST" action="{{ route('admin.invitations.update', $invitation) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="template_id" value="{{ $template->id }}">
    <input type="hidden" name="status" id="statusInput" value="{{ $invitation->status }}">
    
    <div class="row g-6">
        <!-- Main Form Column -->
        <div class="col-xl-8">
            <!-- Groom Information -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Pihak Mempelai Pria</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary">Required fields*</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Upload Foto Mempelai Pria</label>
                            @if($invitation->groom_photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $invitation->groom_photo) }}" class="img-fluid rounded" style="max-height: 100px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_groom_photo" id="removeGroomPhoto" value="1">
                                        <label class="form-check-label text-danger" for="removeGroomPhoto">
                                            Hapus foto yang ada
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('groom_photo') is-invalid @enderror" 
                                   name="groom_photo" id="groomPhoto" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp">
                            <div class="form-text">Format: JPG, PNG, JPEG, GIF, WEBP, BMP (Max 5MB). Kosongkan jika tidak ingin mengubah foto.</div>
                            <div id="groomPhotoPreview" class="mt-3" style="display: none;">
                                <img id="groomPhotoImg" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                            @error('groom_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control @error('groom_full_name') is-invalid @enderror" 
                                   name="groom_full_name" value="{{ old('groom_full_name', $invitation->groom_full_name) }}" 
                                   placeholder="Nama lengkap mempelai pria">
                            @error('groom_full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Panggilan</label>
                            <input type="text" class="form-control @error('groom_nickname') is-invalid @enderror" 
                                   name="groom_nickname" value="{{ old('groom_nickname', $invitation->groom_nickname) }}" 
                                   placeholder="Nama panggilan">
                            @error('groom_nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Bapak</label>
                            <input type="text" class="form-control @error('groom_father_name') is-invalid @enderror" 
                                   name="groom_father_name" value="{{ old('groom_father_name', $invitation->groom_father_name) }}" 
                                   placeholder="Nama ayah kandung">
                            @error('groom_father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Ibu</label>
                            <input type="text" class="form-control @error('groom_mother_name') is-invalid @enderror" 
                                   name="groom_mother_name" value="{{ old('groom_mother_name', $invitation->groom_mother_name) }}" 
                                   placeholder="Nama ibu kandung">
                            @error('groom_mother_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Alamat</label>
                            <textarea class="form-control @error('groom_address') is-invalid @enderror" 
                                      name="groom_address" rows="3" 
                                      placeholder="Alamat lengkap mempelai pria">{{ old('groom_address', $invitation->groom_address) }}</textarea>
                            @error('groom_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bride Information -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Pihak Mempelai Wanita</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary">Required fields*</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Upload Foto Mempelai Wanita</label>
                            @if($invitation->bride_photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $invitation->bride_photo) }}" class="img-fluid rounded" style="max-height: 100px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_bride_photo" id="removeBridePhoto" value="1">
                                        <label class="form-check-label text-danger" for="removeBridePhoto">
                                            Hapus foto yang ada
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('bride_photo') is-invalid @enderror" 
                                   name="bride_photo" id="bridePhoto" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp">
                            <div class="form-text">Format: JPG, PNG, JPEG, GIF, WEBP, BMP (Max 5MB). Kosongkan jika tidak ingin mengubah foto.</div>
                            <div id="bridePhotoPreview" class="mt-3" style="display: none;">
                                <img id="bridePhotoImg" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                            @error('bride_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control @error('bride_full_name') is-invalid @enderror" 
                                   name="bride_full_name" value="{{ old('bride_full_name', $invitation->bride_full_name) }}" 
                                   placeholder="Nama lengkap mempelai wanita">
                            @error('bride_full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Panggilan</label>
                            <input type="text" class="form-control @error('bride_nickname') is-invalid @enderror" 
                                   name="bride_nickname" value="{{ old('bride_nickname', $invitation->bride_nickname) }}" 
                                   placeholder="Nama panggilan">
                            @error('bride_nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Bapak</label>
                            <input type="text" class="form-control @error('bride_father_name') is-invalid @enderror" 
                                   name="bride_father_name" value="{{ old('bride_father_name', $invitation->bride_father_name) }}" 
                                   placeholder="Nama ayah kandung">
                            @error('bride_father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Ibu</label>
                            <input type="text" class="form-control @error('bride_mother_name') is-invalid @enderror" 
                                   name="bride_mother_name" value="{{ old('bride_mother_name', $invitation->bride_mother_name) }}" 
                                   placeholder="Nama ibu kandung">
                            @error('bride_mother_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Alamat</label>
                            <textarea class="form-control @error('bride_address') is-invalid @enderror" 
                                      name="bride_address" rows="3" 
                                      placeholder="Alamat lengkap mempelai wanita">{{ old('bride_address', $invitation->bride_address) }}</textarea>
                            @error('bride_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Wedding Ceremony Details -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Wedding Ceremony Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch form-check-custom form-check-solid mb-6">
                        <input type="hidden" name="akadNikahToggle" value="0">
                        <input class="form-check-input" type="checkbox" name="akadNikahToggle" id="akadNikahToggle" value="1" 
                               {{ $invitation->has_akad ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="akadNikahToggle">
                            Enable Akad Nikah Ceremony
                        </label>
                    </div>
                    
                    <div id="akadNikahForm" style="{{ $invitation->has_akad ? 'display: block;' : 'display: none;' }}">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="required fw-bold mb-2">Tanggal Akad Nikah</label>
                                <input type="date" class="form-control" name="akad_date" 
                                       value="{{ old('akad_date', $invitation->akad_date ? $invitation->akad_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-bold mb-2">Jam Akad Nikah</label>
                                <input type="time" class="form-control" name="akad_time" 
                                       value="{{ old('akad_time', $invitation->akad_time ? $invitation->akad_time->format('H:i') : '') }}">
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="required fw-bold mb-2">Lokasi Akad Nikah</label>
                                <input type="text" class="form-control" name="akad_location" 
                                       value="{{ old('akad_location', $invitation->akad_location) }}" 
                                       placeholder="Alamat lengkap lokasi akad nikah">
                            </div>
                        </div>
                    </div>
                    
                    <div class="separator my-5"></div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid mb-6">
                        <input type="hidden" name="resepsiToggle" value="0">
                        <input class="form-check-input" type="checkbox" name="resepsiToggle" id="resepsiToggle" value="1" 
                               {{ $invitation->has_reception ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="resepsiToggle">
                            Enable Reception
                        </label>
                    </div>
                    
                    <div id="resepsiForm" style="{{ $invitation->has_reception ? 'display: block;' : 'display: none;' }}">
                        <div id="receptionsContainer">
                            @php
                                $receptions = $invitation->getReceptionDates();
                            @endphp
                            @if($receptions->count() > 0)
                                @foreach($receptions as $index => $reception)
                                <div class="reception-item mb-4 p-4 border rounded">
                                    <div class="row mb-6">
                                        <div class="col-md-12">
                                            <label class="fw-bold mb-2">Nama Resepsi</label>
                                            <input type="text" class="form-control" 
                                                   name="receptions[{{ $index }}][name]" 
                                                   value="{{ $reception['name'] }}" 
                                                   placeholder="e.g., Wedding Reception Day {{ $index + 1 }}">
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-md-6">
                                            <label class="required fw-bold mb-2">Tanggal Resepsi</label>
                                            <input type="date" class="form-control reception-date" 
                                                   name="receptions[{{ $index }}][date]" 
                                                   value="{{ $reception['date'] }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="required fw-bold mb-2">Tempat Resepsi</label>
                                            <input type="text" class="form-control" 
                                                   name="receptions[{{ $index }}][location]" 
                                                   value="{{ $reception['location'] }}" 
                                                   placeholder="Alamat lengkap lokasi resepsi">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light-danger remove-reception-btn">
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                                @endforeach
                                @php $receptionCounter = $receptions->count(); @endphp
                            @else
                                <div class="reception-item mb-4">
                                    <div class="row mb-6">
                                        <div class="col-md-12">
                                            <label class="fw-bold mb-2">Nama Resepsi</label>
                                            <input type="text" class="form-control" name="receptions[0][name]" placeholder="e.g., Wedding Reception Day 1">
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-md-6">
                                            <label class="required fw-bold mb-2">Tanggal Resepsi</label>
                                            <input type="date" class="form-control reception-date" name="receptions[0][date]">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="required fw-bold mb-2">Tempat Resepsi</label>
                                            <input type="text" class="form-control" name="receptions[0][location]" placeholder="Alamat lengkap lokasi resepsi">
                                        </div>
                                    </div>
                                </div>
                                @php $receptionCounter = 1; @endphp
                            @endif
                        </div>
                        <button type="button" class="btn btn-light-primary btn-sm" id="addReceptionBtn">
                            <i class="bi bi-plus"></i>
                            Tambah Tanggal Resepsi
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Location Maps -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Location Maps</h3>
                </div>
                <div class="card-body">
                    <div id="mapsContainer">
                        @php
                            $maps = $invitation->getMaps();
                        @endphp
                        @if($maps->count() > 0)
                            @foreach($maps as $index => $map)
                            <div class="map-item mb-6">
                                <label class="fw-bold mb-2">Map Link/Embed Code {{ $index + 1 }}</label>
                                <textarea class="form-control" name="maps[{{ $index }}]" rows="3" placeholder="Google Maps embed code or link">{{ $map }}</textarea>
                                <button type="button" class="btn btn-sm btn-light-danger mt-2 remove-map-btn">
                                    <i class="bi bi-trash"></i>
                                    Hapus Map
                                </button>
                            </div>
                            @endforeach
                            @php $mapCounter = $maps->count(); @endphp
                        @else
                            <div class="map-item mb-6">
                                <label class="fw-bold mb-2">Map Link/Embed Code 1</label>
                                <textarea class="form-control" name="maps[0]" rows="3" placeholder="Google Maps embed code or link"></textarea>
                            </div>
                            @php $mapCounter = 1; @endphp
                        @endif
                    </div>
                    <button type="button" class="btn btn-light-primary btn-sm" id="addMapBtn">
                        <i class="bi bi-plus"></i>
                        Tambah Map
                    </button>
                </div>
            </div>
            
            <!-- Gift Information -->
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Gift Information</h3>
                    <div class="form-check form-switch form-check-custom form-check-solid m-0">
                        <input type="hidden" name="giftToggle" value="0">
                        <input class="form-check-input" type="checkbox" name="giftToggle" id="giftToggle" value="1" 
                               {{ $invitation->has_gift ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold mb-0" for="giftToggle">Enable Gift</label>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Upload Gift Image</label>
                            @if($invitation->gift_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $invitation->gift_image) }}" class="img-fluid rounded" style="max-height: 100px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_gift_image" id="removeGiftImage" value="1">
                                        <label class="form-check-label text-danger" for="removeGiftImage">
                                            Hapus gambar yang ada
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('gift_image') is-invalid @enderror" 
                                   name="gift_image" id="giftImage" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp">
                            <div class="form-text">Format: JPG, PNG, JPEG, GIF, WEBP, BMP (Max 5MB)</div>
                            <div id="giftImagePreview" class="mt-3" style="display: none;">
                                <img id="giftImageImg" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                            </div>
                            @error('gift_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div id="bankAccountsContainer">
                        @php
                            $bankAccounts = $invitation->getBankAccounts();
                        @endphp
                        @if($bankAccounts->count() > 0)
                            @foreach($bankAccounts as $index => $bank)
                            <div class="bank-account-item mb-6 p-4 border rounded">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="fw-bold mb-2">Nama Bank</label>
                                        <input type="text" class="form-control" name="bank_accounts[{{ $index }}][bank_name]" 
                                               value="{{ $bank['bank_name'] }}" placeholder="e.g., BCA, Mandiri, BRI">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-2">Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control" name="bank_accounts[{{ $index }}][account_name]" 
                                               value="{{ $bank['account_name'] }}" placeholder="Nama sesuai rekening">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-2">Nomor Rekening</label>
                                        <input type="text" class="form-control" name="bank_accounts[{{ $index }}][account_number]" 
                                               value="{{ $bank['account_number'] }}" placeholder="Nomor rekening">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-danger remove-bank-btn">
                                    <i class="bi bi-trash"></i>
                                    Hapus
                                </button>
                            </div>
                            @endforeach
                            @php $bankAccountCounter = $bankAccounts->count(); @endphp
                        @else
                            <div class="bank-account-item mb-6 p-4 border rounded">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="fw-bold mb-2">Nama Bank</label>
                                        <input type="text" class="form-control" name="bank_accounts[0][bank_name]" placeholder="e.g., BCA, Mandiri, BRI">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-2">Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control" name="bank_accounts[0][account_name]" placeholder="Nama sesuai rekening">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold mb-2">Nomor Rekening</label>
                                        <input type="text" class="form-control" name="bank_accounts[0][account_number]" placeholder="Nomor rekening">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-light-danger remove-bank-btn" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                    Hapus
                                </button>
                            </div>
                            @php $bankAccountCounter = 1; @endphp
                        @endif
                    </div>
                    <button type="button" class="btn btn-light-primary btn-sm" id="addBankAccountBtn">
                        <i class="bi bi-plus"></i>
                        Tambah Nomor Rekening
                    </button>
                </div>
            </div>
            
            <!-- Gallery -->
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Gallery</h3>
                    <div class="form-check form-switch form-check-custom form-check-solid m-0">
                        <input type="hidden" name="galleryToggle" value="0">
                        <input class="form-check-input" type="checkbox" name="galleryToggle" id="galleryToggle" value="1" 
                               {{ $invitation->has_gallery ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold mb-0" for="galleryToggle">Enable Gallery</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="fw-bold mb-2">Upload Photos</label>
                        <input type="file" class="form-control @error('gallery_photos.*') is-invalid @enderror" 
                               name="gallery_photos[]" id="galleryPhotos" 
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp" multiple>
                        <div class="form-text">Format: JPG, PNG, JPEG, GIF, WEBP, BMP (Max 10MB each)</div>
                        <div id="galleryPhotosPreview" class="row g-3 mt-3">
                            @php
                                $gallery = $invitation->getGallery();
                                $photos = $gallery['photos'];
                            @endphp
                            @foreach($photos as $index => $photo)
                            <div class="col-md-4 mb-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-existing-photo" 
                                            data-photo="{{ $photo }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('gallery_photos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="fw-bold mb-2">Upload Videos</label>
                        <input type="file" class="form-control @error('gallery_videos.*') is-invalid @enderror" 
                               name="gallery_videos[]" id="galleryVideos" 
                               accept="video/mp4,video/mpeg,video/quicktime,video/avi,video/mov,video/wmv,video/flv" multiple>
                        <div class="form-text">Format: MP4, MPEG, MOV, AVI, WMV, FLV (Max 200MB each)</div>
                        <div id="galleryVideosPreview" class="row g-3 mt-3">
                            @php
                                $videos = $gallery['videos'];
                            @endphp
                            @foreach($videos as $index => $video)
                            <div class="col-md-4 mb-3">
                                <div class="position-relative">
                                    <video class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" controls>
                                        <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                    </video>
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-existing-video" 
                                            data-video="{{ $video }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('gallery_videos.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-xl-4">  
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Invitation Template</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Tema Undangan</label>
                        <input type="text" class="form-control" value="{{ $template->name }}" readonly>
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                    </div>
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Template Version</label>
                        <input type="text" class="form-control" value="v{{ $template->version }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Category</label>
                        <input type="text" class="form-control" value="{{ ucfirst($template->category) }}" readonly>
                    </div>
                    @if($template->description)
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Description</label>
                        <textarea class="form-control" rows="2" readonly>{{ $template->description }}</textarea>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Fitur Undangan</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_wish_active" value="1" 
                               {{ $invitation->is_wish_active ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Aktifkan Ucapan & RSVP
                        </label>
                    </div>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Status</h3>
                </div>
                <div class="card-body">
                    @if($invitation->status == 'published')
                        <div class="alert alert-success">
                            <i class="bi bi-globe"></i> Invitation is <strong>PUBLISHED</strong> and publicly accessible.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-file-earmark"></i> Invitation is in <strong>DRAFT</strong> mode. Not accessible to public.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="d-flex justify-content-end gap-3 mt-6">
        <a href="{{ route('admin.invitations.show', $invitation) }}" class="btn btn-light">
            Cancel
        </a>
        <button type="button" class="btn btn-secondary" id="saveDraftBtn">
            <i class="bi bi-save"></i> Save as Draft
        </button>
        @if($invitation->status != 'published')
        <button type="button" class="btn btn-success" id="publishBtn">
            <i class="bi bi-globe"></i> Publish Invitation
        </button>
        @endif
        <button type="button" class="btn btn-primary" id="updateBtn">
            <i class="bi bi-save"></i> Update Changes
        </button>
    </div>
</form>

@push('scripts')
<script>
    let receptionCounter = {{ $receptionCounter ?? 1 }};
    let mapCounter = {{ $mapCounter ?? 1 }};
    let bankAccountCounter = {{ $bankAccountCounter ?? 1 }};
    
    // Preview for Groom Photo
    document.getElementById('groomPhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Format file tidak didukung', 'error');
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'Ukuran file maksimal 5MB', 'error');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('groomPhotoPreview');
                const img = document.getElementById('groomPhotoImg');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Preview for Bride Photo
    document.getElementById('bridePhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Format file tidak didukung', 'error');
                this.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'Ukuran file maksimal 5MB', 'error');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('bridePhotoPreview');
                const img = document.getElementById('bridePhotoImg');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Akad Nikah Toggle
    document.getElementById('akadNikahToggle').addEventListener('change', function() {
        document.getElementById('akadNikahForm').style.display = this.checked ? 'block' : 'none';
    });
    
    // Resepsi Toggle
    document.getElementById('resepsiToggle').addEventListener('change', function() {
        document.getElementById('resepsiForm').style.display = this.checked ? 'block' : 'none';
    });
    
    // Add Reception Date
    document.getElementById('addReceptionBtn').addEventListener('click', function() {
        const container = document.getElementById('receptionsContainer');
        const newReception = document.createElement('div');
        newReception.className = 'reception-item mb-4 p-4 border rounded';
        newReception.innerHTML = `
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Nama Resepsi</label>
                    <input type="text" class="form-control" name="receptions[${receptionCounter}][name]" placeholder="e.g., Wedding Reception Day ${receptionCounter + 1}">
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Tanggal Resepsi</label>
                    <input type="date" class="form-control reception-date" name="receptions[${receptionCounter}][date]">
                </div>
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Tempat Resepsi</label>
                    <input type="text" class="form-control" name="receptions[${receptionCounter}][location]" placeholder="Alamat lengkap lokasi resepsi">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light-danger remove-reception-btn">
                <i class="bi bi-trash"></i>
                Hapus
            </button>
        `;
        container.appendChild(newReception);
        newReception.querySelector('.remove-reception-btn').addEventListener('click', function() {
            newReception.remove();
        });
        receptionCounter++;
    });
    
    // Add Map
    document.getElementById('addMapBtn').addEventListener('click', function() {
        const container = document.getElementById('mapsContainer');
        const newMap = document.createElement('div');
        newMap.className = 'map-item mb-6';
        newMap.innerHTML = `
            <label class="fw-bold mb-2">Map Link/Embed Code ${mapCounter + 1}</label>
            <textarea class="form-control" name="maps[${mapCounter}]" rows="3" placeholder="Google Maps embed code or link"></textarea>
            <button type="button" class="btn btn-sm btn-light-danger mt-2 remove-map-btn">
                <i class="bi bi-trash"></i>
                Hapus Map
            </button>
        `;
        container.appendChild(newMap);
        newMap.querySelector('.remove-map-btn').addEventListener('click', function() {
            newMap.remove();
        });
        mapCounter++;
    });
    
    // Add Bank Account
    document.getElementById('addBankAccountBtn').addEventListener('click', function() {
        const container = document.getElementById('bankAccountsContainer');
        const newBankAccount = document.createElement('div');
        newBankAccount.className = 'bank-account-item mb-6 p-4 border rounded';
        newBankAccount.innerHTML = `
            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Nama Bank</label>
                    <input type="text" class="form-control" name="bank_accounts[${bankAccountCounter}][bank_name]" placeholder="e.g., BCA, Mandiri, BRI">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Nama Pemilik Rekening</label>
                    <input type="text" class="form-control" name="bank_accounts[${bankAccountCounter}][account_name]" placeholder="Nama sesuai rekening">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Nomor Rekening</label>
                    <input type="text" class="form-control" name="bank_accounts[${bankAccountCounter}][account_number]" placeholder="Nomor rekening">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light-danger remove-bank-btn">
                <i class="bi bi-trash"></i>
                Hapus
            </button>
        `;
        container.appendChild(newBankAccount);
        newBankAccount.querySelector('.remove-bank-btn').addEventListener('click', function() {
            newBankAccount.remove();
        });
        bankAccountCounter++;
    });
    
    // Remove existing photo handler
    document.querySelectorAll('.remove-existing-photo').forEach(btn => {
        btn.addEventListener('click', function() {
            const photo = this.getAttribute('data-photo');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_gallery_photos[]';
            input.value = photo;
            document.getElementById('invitationForm').appendChild(input);
            this.closest('.col-md-4').remove();
        });
    });
    
    // Remove existing video handler
    document.querySelectorAll('.remove-existing-video').forEach(btn => {
        btn.addEventListener('click', function() {
            const video = this.getAttribute('data-video');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_gallery_videos[]';
            input.value = video;
            document.getElementById('invitationForm').appendChild(input);
            this.closest('.col-md-4').remove();
        });
    });
    
    // Form validation
    function validateWeddingForm() {
        const requiredFields = [
            'groom_full_name', 'groom_nickname', 'groom_father_name', 
            'groom_mother_name', 'groom_address',
            'bride_full_name', 'bride_nickname', 'bride_father_name', 
            'bride_mother_name', 'bride_address'
        ];
        
        for(let field of requiredFields) {
            const input = document.querySelector(`[name="${field}"]`);
            if(input && !input.value.trim()) {
                Swal.fire('Error', 'Please fill in all required fields', 'error');
                return false;
            }
        }
        
        if(document.getElementById('akadNikahToggle').checked) {
            const akadDate = document.querySelector('[name="akad_date"]');
            const akadTime = document.querySelector('[name="akad_time"]');
            const akadLocation = document.querySelector('[name="akad_location"]');
            if(!akadDate.value || !akadTime.value || !akadLocation.value) {
                Swal.fire('Error', 'Please complete all Akad Nikah details', 'error');
                return false;
            }
        }
        
        if(document.getElementById('resepsiToggle').checked) {
            const receptions = document.querySelectorAll('.reception-item');
            for(let reception of receptions) {
                const name = reception.querySelector('[name*="[name]"]');
                const date = reception.querySelector('[name*="[date]"]');
                const location = reception.querySelector('[name*="[location]"]');
                if(!name.value || !date.value || !location.value) {
                    Swal.fire('Error', 'Please complete all reception details', 'error');
                    return false;
                }
            }
        }
        
        return true;
    }
    
    // Submit buttons
    document.getElementById('saveDraftBtn').onclick = function(e) {
        e.preventDefault();
        if(validateWeddingForm()) {
            document.getElementById('statusInput').value = 'draft';
            document.getElementById('invitationForm').submit();
        }
    }
    
    const publishBtn = document.getElementById('publishBtn');
    if(publishBtn) {
        publishBtn.onclick = function(e) {
            e.preventDefault();
            if(validateWeddingForm()) {
                document.getElementById('statusInput').value = 'published';
                document.getElementById('invitationForm').submit();
            }
        }
    }
    
    document.getElementById('updateBtn').onclick = function(e) {
        e.preventDefault();
        if(validateWeddingForm()) {
            const currentStatus = document.getElementById('statusInput').value;
            document.getElementById('statusInput').value = currentStatus;
            document.getElementById('invitationForm').submit();
        }
    }
</script>
@endpush
@endsection