@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">My Profile</li>
@endsection

@section('content')
<div class="row g-6">
    <!-- Profile Info -->
    <div class="col-xl-4">
        <div class="card mb-6">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" 
                         class="rounded-circle w-150px h-150px object-fit-cover border border-4 border-primary">
                    <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                            onclick="$('#avatarUpload').click()">
                        <i class="bi bi-camera"></i>
                    </button>
                </div>
                <form id="avatarForm" action="{{ route('admin.profile.photo.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" name="avatar" id="avatarUpload" accept="image/*">
                </form>
                
                <h3 class="mt-3">{{ auth()->user()->name }}</h3>
                <p class="text-muted">{{ auth()->user()->email }}</p>
                <div class="mt-3">
                    <span class="badge badge-light-{{ auth()->user()->status == 'active' ? 'success' : (auth()->user()->status == 'inactive' ? 'warning' : 'danger') }}">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Account Info</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Last Login</span>
                    <span class="fw-bold">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Member Since</span>
                    <span class="fw-bold">{{ auth()->user()->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Timezone</span>
                    <span class="fw-bold">{{ auth()->user()->timezone }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Form -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#profile">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#password">
                            <i class="bi bi-key"></i> Password
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#activity">
                            <i class="bi bi-clock-history"></i> Activity
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile">
                        <form method="POST" action="{{ route('admin.profile.update') }}">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          name="address" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                    
                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="password">
                        <form method="POST" action="{{ route('admin.profile.password.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label class="required fw-bold mb-2">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="required fw-bold mb-2">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                <div class="form-text">Minimum 8 characters</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="required fw-bold mb-2">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Module</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(auth()->user()->activityLogs()->latest()->limit(20)->get() as $log)
                                    <tr>
                                        <td><span class="badge badge-light-info">{{ ucfirst($log->action) }}</span></td>
                                        <td>{{ ucfirst($log->module) }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No activity logs found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Avatar upload
    document.getElementById('avatarUpload').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            const formData = new FormData();
            formData.append('avatar', e.target.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            $.ajax({
                url: '{{ route("admin.profile.photo.update") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection