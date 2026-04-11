{{-- resources/views/admin/invitations/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Wedding Invitation')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.invitations.index') }}" class="text-muted text-hover-primary">
        All Invitations
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Create Wedding Invitation</li>
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
<form id="invitationForm" method="POST" action="{{ route('admin.invitations.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="template_id" value="{{ $template->id }}">
    <input type="hidden" name="status" id="statusInput" value="draft">
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
                            <input type="file" class="form-control @error('groom_photo') is-invalid @enderror" name="groom_photo" id="groomPhoto" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp">
                            <div class="form-text">Format: JPG, PNG, JPEG, GIF, WEBP, BMP (Max 5MB)</div>
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
                            <input type="text" class="form-control @error('groom_full_name') is-invalid @enderror" name="groom_full_name" placeholder="Nama lengkap mempelai pria">
                            @error('groom_full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Panggilan</label>
                            <input type="text" class="form-control @error('groom_nickname') is-invalid @enderror" name="groom_nickname" placeholder="Nama panggilan">
                            @error('groom_nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Bapak</label>
                            <input type="text" class="form-control @error('groom_father_name') is-invalid @enderror" name="groom_father_name" placeholder="Nama ayah kandung">
                            @error('groom_father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Ibu</label>
                            <input type="text" class="form-control @error('groom_mother_name') is-invalid @enderror" name="groom_mother_name" placeholder="Nama ibu kandung">
                            @error('groom_mother_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Alamat</label>
                            <textarea class="form-control @error('groom_address') is-invalid @enderror" name="groom_address" rows="3" placeholder="Alamat lengkap mempelai pria"></textarea>
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
                            <input type="file" class="form-control @error('bride_photo') is-invalid @enderror" name="bride_photo" id="bridePhoto" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp">
                            <div class="form-text">Format: JPG, PNG, JPEG, GIF, WEBP, BMP (Max 5MB)</div>
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
                            <input type="text" class="form-control @error('bride_full_name') is-invalid @enderror" name="bride_full_name" placeholder="Nama lengkap mempelai wanita">
                            @error('bride_full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Panggilan</label>
                            <input type="text" class="form-control @error('bride_nickname') is-invalid @enderror" name="bride_nickname" placeholder="Nama panggilan">
                            @error('bride_nickname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Bapak</label>
                            <input type="text" class="form-control @error('bride_father_name') is-invalid @enderror" name="bride_father_name" placeholder="Nama ayah kandung">
                            @error('bride_father_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Ibu</label>
                            <input type="text" class="form-control @error('bride_mother_name') is-invalid @enderror" name="bride_mother_name" placeholder="Nama ibu kandung">
                            @error('bride_mother_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Alamat</label>
                            <textarea class="form-control @error('bride_address') is-invalid @enderror" name="bride_address" rows="3" placeholder="Alamat lengkap mempelai wanita"></textarea>
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
                        <input class="form-check-input" type="checkbox" name="akadNikahToggle" id="akadNikahToggle" value="1">
                        <label class="form-check-label fw-bold" for="akadNikahToggle">
                            Enable Akad Nikah Ceremony
                        </label>
                    </div>
                    
                    <div id="akadNikahForm" style="display: none;">
                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="required fw-bold mb-2">Tanggal Akad Nikah</label>
                                <input type="date" class="form-control" name="akad_date" id="akadDate">
                            </div>
                            <div class="col-md-6">
                                <label class="required fw-bold mb-2">Jam Akad Nikah</label>
                                <input type="time" class="form-control" name="akad_time">
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-12">
                                <label class="required fw-bold mb-2">Lokasi Akad Nikah</label>
                                <input type="text" class="form-control" name="akad_location" placeholder="Alamat lengkap lokasi akad nikah">
                            </div>
                        </div>
                    </div>
                    
                    <div class="separator my-5"></div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid mb-6">
                        <input type="hidden" name="resepsiToggle" value="0">
                        <input class="form-check-input" type="checkbox" name="resepsiToggle" id="resepsiToggle" value="1">
                        <label class="form-check-label fw-bold" for="resepsiToggle">
                            Enable Reception
                        </label>
                    </div>
                    
                    <div id="resepsiForm" style="display: none;">
                        <div id="receptionsContainer">
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
                        <div class="map-item mb-6">
                            <label class="fw-bold mb-2">Map Link/Embed Code 1</label>
                            <textarea class="form-control" name="maps[0]" rows="3" placeholder="Google Maps embed code or link"></textarea>
                            <div class="form-text">Paste Google Maps embed code or shareable link</div>
                        </div>
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
                        <input class="form-check-input" type="checkbox" name="giftToggle" id="giftToggle" value="1" checked>
                        <label class="form-check-label fw-bold mb-0" for="giftToggle">Enable Gift</label>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Upload Gift Image</label>
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
                        <input class="form-check-input" type="checkbox" name="galleryToggle" id="galleryToggle" value="1" checked>
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
                        <div id="galleryPhotosPreview" class="row g-3 mt-3"></div>
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
                        <div id="galleryVideosPreview" class="row g-3 mt-3"></div>
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
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <small>Template settings can be customized after the invitation is created.</small>
                    </div>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Fitur Undangan</h3>
                </div>
                <div class="card-body">

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_wish_active" value="1" checked>
                        <label class="form-check-label">
                            Aktifkan Ucapan & RSVP
                        </label>
                    </div>

                </div>
            </div>

        </div>
    </div>
    
    <!-- Action Buttons -->
    <input type="hidden" name="status" id="statusInput" value="draft">
    <div class="d-flex justify-content-end gap-3 mt-6">
        <a href="{{ route('admin.invitations.index') }}" class="btn btn-light">
            Cancel
        </a>
        <button type="button" class="btn btn-secondary" id="saveDraftBtn">
            <i class="bi bi-save"></i>
            Save as Draft
        </button>
        <button type="button" class="btn btn-success" id="publishBtn">
            <i class="bi bi-globe"></i> Publish Invitation
        </button>
        <button type="button" class="btn btn-primary" id="createAndPublishBtn">
            <i class="bi bi-send"></i> Create & Publish
        </button>
    </div>
</form>

@push('scripts')
<script>
    let receptionCounter = 1;
    let mapCounter = 1;
    let bankAccountCounter = 1;
    
    // Preview for Groom Photo dengan validasi
    document.getElementById('groomPhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Format file tidak didukung. Gunakan: JPG, PNG, JPEG, GIF, WEBP, BMP', 'error');
                this.value = '';
                document.getElementById('groomPhotoPreview').style.display = 'none';
                return;
            }
            
            // Validasi ukuran file (5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'Ukuran file maksimal 5MB', 'error');
                this.value = '';
                document.getElementById('groomPhotoPreview').style.display = 'none';
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
        } else {
            document.getElementById('groomPhotoPreview').style.display = 'none';
        }
    });

    // Preview untuk Bride Photo dengan validasi
    document.getElementById('bridePhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Format file tidak didukung. Gunakan: JPG, PNG, JPEG, GIF, WEBP, BMP', 'error');
                this.value = '';
                document.getElementById('bridePhotoPreview').style.display = 'none';
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'Ukuran file maksimal 5MB', 'error');
                this.value = '';
                document.getElementById('bridePhotoPreview').style.display = 'none';
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
        } else {
            document.getElementById('bridePhotoPreview').style.display = 'none';
        }
    });

    // Preview untuk Gift Image dengan validasi
    document.getElementById('giftImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'Format file tidak didukung. Gunakan: JPG, PNG, JPEG, GIF, WEBP, BMP', 'error');
                this.value = '';
                document.getElementById('giftImagePreview').style.display = 'none';
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'Ukuran file maksimal 5MB', 'error');
                this.value = '';
                document.getElementById('giftImagePreview').style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('giftImagePreview');
                const img = document.getElementById('giftImageImg');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('giftImagePreview').style.display = 'none';
        }
    });
    
    // Preview for Gallery Photos (multiple) dengan validasi
    document.getElementById('galleryPhotos').addEventListener('change', function(e) {
        const preview = document.getElementById('galleryPhotosPreview');
        preview.innerHTML = '';
        const files = Array.from(e.target.files);
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/bmp'];
        let hasError = false;
        
        for (let file of files) {
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', `File "${file.name}" format tidak didukung. Gunakan: JPG, PNG, JPEG, GIF, WEBP, BMP`, 'error');
                hasError = true;
                break;
            }
            
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire('Error', `File "${file.name}" ukuran melebihi 10MB`, 'error');
                hasError = true;
                break;
            }
        }
        
        if (hasError) {
            this.value = '';
            return;
        }
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${event.target.result}" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                        <span class="badge badge-primary position-absolute top-0 end-0 m-1">${index + 1}</span>
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
    
    // Preview for Gallery Videos
    document.getElementById('galleryVideos').addEventListener('change', function(e) {
        const preview = document.getElementById('galleryVideosPreview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="position-relative">
                        <video class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;" controls>
                            <source src="${event.target.result}" type="${file.type}">
                        </video>
                        <span class="badge badge-primary position-absolute top-0 end-0 m-1">${index + 1}</span>
                        <button type="button" class="btn btn-sm btn-danger position-absolute bottom-0 end-0 m-1 remove-video-btn" style="display: none;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                preview.appendChild(col);
                
                // Add hover effect to show remove button
                col.addEventListener('mouseenter', function() {
                    const removeBtn = this.querySelector('.remove-video-btn');
                    if(removeBtn) removeBtn.style.display = 'block';
                });
                col.addEventListener('mouseleave', function() {
                    const removeBtn = this.querySelector('.remove-video-btn');
                    if(removeBtn) removeBtn.style.display = 'none';
                });
            };
            reader.readAsDataURL(file);
        });
    });
    
    // Akad Nikah Toggle
    document.getElementById('akadNikahToggle').addEventListener('change', function() {
        const akadForm = document.getElementById('akadNikahForm');
        akadForm.style.display = this.checked ? 'block' : 'none';
    });
    
    // Resepsi Toggle
    document.getElementById('resepsiToggle').addEventListener('change', function() {
        const resepsiForm = document.getElementById('resepsiForm');
        resepsiForm.style.display = this.checked ? 'block' : 'none';
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
        
        // Add remove functionality
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
    
    // Auto-fill day of week for Akad date
    document.getElementById('akadDate').addEventListener('change', function() {
        if(this.value) {
            const date = new Date(this.value);
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[date.getDay()];
            // You can display this day name somewhere if needed
            console.log('Akad date falls on: ' + dayName);
        }
    });
    
    // Auto-fill day of week for Reception dates (using event delegation)
    document.addEventListener('change', function(e) {
        if(e.target && e.target.classList.contains('reception-date')) {
            if(e.target.value) {
                const date = new Date(e.target.value);
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const dayName = days[date.getDay()];
                console.log('Reception date falls on: ' + dayName);
            }
        }
    });
    
    // Form validation before submit
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
                Swal.fire('Error', `Please fill in all required fields for both bride and groom`, 'error');
                return false;
            }
        }
        
        // Validate Akad Nikah if enabled
        if(document.getElementById('akadNikahToggle').checked) {
            const akadDate = document.querySelector('[name="akad_date"]');
            const akadTime = document.querySelector('[name="akad_time"]');
            const akadLocation = document.querySelector('[name="akad_location"]');
            
            if(!akadDate.value || !akadTime.value || !akadLocation.value) {
                Swal.fire('Error', 'Please complete all Akad Nikah details', 'error');
                return false;
            }
        }
        
        // Validate Reception if enabled
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
    
    // Fix the submit buttons
    document.getElementById('saveDraftBtn').onclick = function(e) {
        e.preventDefault();
        if(validateWeddingForm()) {
            document.getElementById('statusInput').value = 'draft';
            document.getElementById('invitationForm').submit();
        }
    }

    document.getElementById('publishBtn').onclick = function(e) {
        e.preventDefault();
        if(validateWeddingForm()) {
            document.getElementById('statusInput').value = 'published';
            document.getElementById('invitationForm').submit();
        }
    }

    document.getElementById('createAndPublishBtn').onclick = function(e) {
        e.preventDefault();
        if(validateWeddingForm()) {
            document.getElementById('statusInput').value = 'published';
            document.getElementById('invitationForm').submit();
        }
    }

</script>
@endpush
@endsection