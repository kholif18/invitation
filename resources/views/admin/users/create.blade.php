{{-- resources/views/admin/users/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create User')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.users.index') }}" class="text-muted text-hover-primary">
        Users
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Create User</li>
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-6">
        <div class="col-xl-8">
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Basic Information</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" placeholder="Full Name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" placeholder="Email Address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" placeholder="Phone Number">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Avatar</label>
                            <input type="file" class="form-control" name="avatar" accept="image/*" id="avatarInput">
                            <div class="form-text">Max 2MB, JPG, JPEG, PNG</div>
                            <div id="avatarPreview" class="mt-3" style="display: none;">
                                <img id="previewImg" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" id="password" placeholder="Password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" 
                                   id="passwordConfirmation" placeholder="Confirm Password" required>
                            <div id="passwordMatch" class="form-text"></div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="fw-bold mb-2">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  name="address" rows="3" placeholder="Address"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Account Settings</h3>
                </div>
                <div class="card-body">
                    <div class="mb-6">
                        <label class="required fw-bold mb-2">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="banned">Banned</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-3 mt-6">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary">Create User</button>
    </div>
</form>

@push('scripts')
<script>
    // Avatar preview
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB

            // ❌ Validasi ukuran
            if (file.size > maxSize) {
                Swal.fire('Error', 'Ukuran file maksimal 2MB', 'error');
                e.target.value = ''; // reset input
                document.getElementById('avatarPreview').style.display = 'none';
                return;
            }

            // ❌ Validasi tipe (optional tambahan biar aman)
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire('Error', 'File harus JPG atau PNG', 'error');
                e.target.value = '';
                document.getElementById('avatarPreview').style.display = 'none';
                return;
            }

            // ✅ Preview kalau lolos
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('avatarPreview');
                const img = document.getElementById('previewImg');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('avatarPreview').style.display = 'none';
        }
    });
    
    // Password match validation
    const password = document.getElementById('password');
    const confirm = document.getElementById('passwordConfirmation');
    const matchDiv = document.getElementById('passwordMatch');
    
    function validatePassword() {
        if(password.value !== confirm.value) {
            matchDiv.innerHTML = '<span class="text-danger">Passwords do not match</span>';
            return false;
        } else if(password.value.length > 0 && confirm.value.length > 0) {
            matchDiv.innerHTML = '<span class="text-success">Passwords match</span>';
            return true;
        } else {
            matchDiv.innerHTML = '';
            return false;
        }
    }
    
    password.addEventListener('keyup', validatePassword);
    confirm.addEventListener('keyup', validatePassword);
    
    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        if(password.value !== confirm.value) {
            e.preventDefault();
            Swal.fire('Error', 'Passwords do not match', 'error');
        }
    });
</script>
@endpush
@endsection