{{-- resources/views/admin/users/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit User')

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
<li class="breadcrumb-item text-dark">Edit User</li>
@endsection

@section('content')
<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
                                   name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Avatar</label>
                            <input type="file" class="form-control" name="avatar" accept="image/*" id="avatarInput">
                            <div class="form-text">Max 2MB, JPG, JPEG, PNG. Leave empty to keep current avatar.</div>
                            <div class="mt-3">
                                <div id="currentAvatar" class="mb-2">
                                    <span class="text-muted">Current Avatar:</span>
                                    <img src="{{ $user->avatar_url }}" class="rounded ms-2" style="width: 50px; height: 50px; object-fit: cover;">
                                </div>
                                <div id="avatarPreview" style="display: none;">
                                    <span class="text-muted">New Avatar Preview:</span>
                                    <img id="previewImg" class="rounded ms-2" style="width: 50px; height: 50px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="fw-bold mb-2">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Change Password</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-info">Optional</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Leave password fields empty to keep current password.
                    </div>
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="newPassword">
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-2">Confirm New Password</label>
                            <input type="password" class="form-control" name="new_password_confirmation" id="confirmPassword">
                            <div id="passwordMatch" class="form-text"></div>
                        </div>
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
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Account Information</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">User ID</span>
                        <span class="fw-bold">#{{ $user->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Last Login</span>
                        <span class="fw-bold">{{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : 'Never' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Member Since</span>
                        <span class="fw-bold">{{ $user->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end gap-3 mt-6">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light-info">View User</a>
        <button type="submit" class="btn btn-primary">Update User</button>
    </div>
</form>

@push('scripts')
<script>
    // Avatar preview
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const currentAvatar = document.getElementById('currentAvatar');
        const previewDiv = document.getElementById('avatarPreview');
        
        if(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.getElementById('previewImg');
                img.src = event.target.result;
                previewDiv.style.display = 'block';
                currentAvatar.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.style.display = 'none';
            currentAvatar.style.display = 'block';
        }
    });
    
    // Password match validation
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const matchDiv = document.getElementById('passwordMatch');
    
    function validatePassword() {
        if(newPassword.value.length === 0 && confirmPassword.value.length === 0) {
            matchDiv.innerHTML = '<span class="text-muted">Password will remain unchanged</span>';
            return true;
        }
        
        if(newPassword.value !== confirmPassword.value) {
            matchDiv.innerHTML = '<span class="text-danger">Passwords do not match</span>';
            return false;
        } else if(newPassword.value.length < 8 && newPassword.value.length > 0) {
            matchDiv.innerHTML = '<span class="text-danger">Password must be at least 8 characters</span>';
            return false;
        } else if(newPassword.value.length > 0 && confirmPassword.value.length > 0) {
            matchDiv.innerHTML = '<span class="text-success">Passwords match</span>';
            return true;
        } else {
            matchDiv.innerHTML = '';
            return false;
        }
    }
    
    newPassword.addEventListener('keyup', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);
    
    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const newPass = newPassword.value;
        const confirm = confirmPassword.value;
        
        if(newPass !== confirm) {
            e.preventDefault();
            Swal.fire('Error', 'Passwords do not match', 'error');
        } else if(newPass.length > 0 && newPass.length < 8) {
            e.preventDefault();
            Swal.fire('Error', 'Password must be at least 8 characters', 'error');
        }
    });
</script>
@endpush
@endsection