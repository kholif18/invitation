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
<form id="invitationForm" enctype="multipart/form-data">
    @csrf
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
                            <input type="file" class="form-control" name="groom_photo" id="groomPhoto" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, JPEG (Max 5MB)</div>
                            <div id="groomPhotoPreview" class="mt-3" style="display: none;">
                                <img id="groomPhotoImg" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control" name="groom_full_name" placeholder="Nama lengkap mempelai pria">
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Panggilan</label>
                            <input type="text" class="form-control" name="groom_nickname" placeholder="Nama panggilan">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Bapak</label>
                            <input type="text" class="form-control" name="groom_father_name" placeholder="Nama ayah kandung">
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Ibu</label>
                            <input type="text" class="form-control" name="groom_mother_name" placeholder="Nama ibu kandung">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Alamat</label>
                            <textarea class="form-control" name="groom_address" rows="3" placeholder="Alamat lengkap mempelai pria"></textarea>
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
                            <input type="file" class="form-control" name="bride_photo" id="bridePhoto" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, JPEG (Max 5MB)</div>
                            <div id="bridePhotoPreview" class="mt-3" style="display: none;">
                                <img id="bridePhotoImg" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control" name="bride_full_name" placeholder="Nama lengkap mempelai wanita">
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Panggilan</label>
                            <input type="text" class="form-control" name="bride_nickname" placeholder="Nama panggilan">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Bapak</label>
                            <input type="text" class="form-control" name="bride_father_name" placeholder="Nama ayah kandung">
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Nama Ibu</label>
                            <input type="text" class="form-control" name="bride_mother_name" placeholder="Nama ibu kandung">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Alamat</label>
                            <textarea class="form-control" name="bride_address" rows="3" placeholder="Alamat lengkap mempelai wanita"></textarea>
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
                        <input class="form-check-input" type="checkbox" id="akadNikahToggle">
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
                        <input class="form-check-input" type="checkbox" id="resepsiToggle">
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
                        <input class="form-check-input" type="checkbox" id="giftToggle" checked>
                        <label class="form-check-label fw-bold mb-0" for="giftToggle">Enable Gift</label>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Upload Gift Image</label>
                            <input type="file" class="form-control" name="gift_image" id="giftImage" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, JPEG (Max 5MB)</div>
                            <div id="giftImagePreview" class="mt-3" style="display: none;">
                                <img id="giftImageImg" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                            </div>
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
                        <input class="form-check-input" type="checkbox" id="galleryToggle" checked>
                        <label class="form-check-label fw-bold mb-0" for="galleryToggle">Enable Gallery</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="fw-bold mb-2">Upload Photos</label>
                        <input type="file" class="form-control" name="gallery_photos[]" id="galleryPhotos" accept="image/*" multiple>
                        <div class="form-text">You can select multiple photos (Max 10MB each)</div>
                        <div id="galleryPhotosPreview" class="row g-3 mt-3"></div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="fw-bold mb-2">Upload Videos</label>
                        <input type="file" class="form-control" name="gallery_videos[]" id="galleryVideos" accept="video/*" multiple>
                        <div class="form-text">You can select multiple videos (Max 200MB each)</div>
                        <div id="galleryVideosPreview" class="row g-3 mt-3"></div>
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
                        <input type="text" class="form-control" value="Tema Jawa" readonly>
                        <input type="hidden" name="template_id" value="tema-jawa">
                    </div>
                    <div>
                        <a href="#" class="btn btn-light-primary">
                            <i class="bi bi-pencil"></i> Sesuaikan / Edit Tema
                        </a>
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
        <button type="button" class="btn btn-primary" id="sendInvitationBtn">
            <i class="bi bi-send"></i>
            Send Invitation
        </button>
    </div>
</form>

@push('scripts')
<script>
    let receptionCounter = 1;
    let mapCounter = 1;
    let bankAccountCounter = 1;
    
    // Preview for Groom Photo
    document.getElementById('groomPhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
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
    
    // Preview for Bride Photo
    document.getElementById('bridePhoto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
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
    
    // Preview for Gift Image
    document.getElementById('giftImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
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
    
    // Preview for Gallery Photos (multiple)
    document.getElementById('galleryPhotos').addEventListener('change', function(e) {
        const preview = document.getElementById('galleryPhotosPreview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${event.target.result}" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                        <span class="badge badge-primary position-absolute top-0 end-0 m-1">${index + 1}</span>
                        <button type="button" class="btn btn-sm btn-danger position-absolute bottom-0 end-0 m-1 remove-photo-btn" data-index="${index}" style="display: none;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                preview.appendChild(col);
                
                // Add hover effect to show remove button
                col.addEventListener('mouseenter', function() {
                    const removeBtn = this.querySelector('.remove-photo-btn');
                    if(removeBtn) removeBtn.style.display = 'block';
                });
                col.addEventListener('mouseleave', function() {
                    const removeBtn = this.querySelector('.remove-photo-btn');
                    if(removeBtn) removeBtn.style.display = 'none';
                });
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
        
        // Check if at least one sending method is selected
        const sendEmail = document.getElementById('sendEmail').checked;
        const sendWhatsapp = document.getElementById('sendWhatsapp').checked;
        
        if(!sendEmail && !sendWhatsapp) {
            Swal.fire('Error', 'Please select at least one sending method (Email or WhatsApp)', 'error');
            return false;
        }
        
        // Check if guests exist
        if(guests.length === 0) {
            Swal.fire('Error', 'Please add at least one guest', 'error');
            return false;
        }
        
        return true;
    }
    
    // Save Draft
    document.getElementById('saveDraftBtn').addEventListener('click', function() {
        if(validateWeddingForm()) {
            Swal.fire({
                icon: 'success',
                title: 'Draft Saved!',
                text: 'Your wedding invitation has been saved as draft.',
                timer: 2000
            });
        }
    });
    
    // Send Invitation
    document.getElementById('sendInvitationBtn').addEventListener('click', function() {
        if(!validateWeddingForm()) return;
        
        Swal.fire({
            title: 'Send Invitation?',
            text: `This will send invitation to ${guests.length} guest(s) via ${getSendingMethods()}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would normally submit the form via AJAX or regular submit
                Swal.fire({
                    icon: 'success',
                    title: 'Invitation Sent!',
                    text: 'Your wedding invitation has been sent successfully.',
                    timer: 2000
                }).then(() => {
                    window.location.href = "{{ route('admin.invitations.index') }}";
                });
            }
        });
    });
    
    function getSendingMethods() {
        const methods = [];
        if(document.getElementById('sendEmail').checked) methods.push('Email');
        if(document.getElementById('sendWhatsapp').checked) methods.push('WhatsApp');
        return methods.join(' and ');
    }

    document.getElementById('saveDraftBtn').onclick = function() {
        document.getElementById('statusInput').value = 'draft';
        document.getElementById('invitationForm').submit();
    }

    document.getElementById('sendInvitationBtn').onclick = function() {
        document.getElementById('statusInput').value = 'published';
        document.getElementById('invitationForm').submit();
    }

</script>
@endpush
@endsection