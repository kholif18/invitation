{{-- resources/views/admin/users/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'User Details')

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
<li class="breadcrumb-item text-dark">User Details</li>
@endsection

@section('content')
<div class="row g-6">
    <!-- User Profile Card -->
    <div class="col-xl-4">
        <div class="card mb-6">
            <div class="card-body text-center">
                <div class="mb-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="rounded-circle w-150px h-150px object-fit-cover border border-4 border-primary">
                </div>
                <h3 class="mb-1">{{ $user->name }}</h3>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                @php
                    $statusColors = [
                        'active' => 'success',
                        'inactive' => 'warning',
                        'banned' => 'danger'
                    ];
                    $statusColor = $statusColors[$user->status] ?? 'secondary';
                @endphp
                <span class="badge badge-light-{{ $statusColor }} fs-6 px-3 py-2">
                    {{ ucfirst($user->status) }}
                </span>
                
                <div class="separator my-5"></div>
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit User
                    </a>
                    @if(auth()->id() != $user->id)
                    <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    @endif
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
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Member Since</span>
                    <span class="fw-bold">{{ $user->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Details -->
    <div class="col-xl-8">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Personal Information</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="text-muted">Full Name</label>
                    </div>
                    <div class="col-md-9">
                        <p class="fw-bold">{{ $user->name }}</p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="text-muted">Email Address</label>
                    </div>
                    <div class="col-md-9">
                        <p class="fw-bold">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="text-muted">Phone Number</label>
                    </div>
                    <div class="col-md-9">
                        <p class="fw-bold">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="text-muted">Address</label>
                    </div>
                    <div class="col-md-9">
                        <p class="fw-bold">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Activity Log -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Date</th>
                             </tr>
                        </thead>
                        <tbody>
                            @forelse($user->activityLogs()->latest()->limit(10)->get() as $log)
                             <tr>
                                 <td>
                                    <span class="badge badge-light-info">{{ ucfirst($log->action) }}</span>
                                 </td>
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
                @if($user->activityLogs()->count() > 10)
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-sm btn-light">View All Activity</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteUser(id, name) {
        Swal.fire({
            title: 'Delete User?',
            html: `Are you sure you want to delete <strong>${name}</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/users') }}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            setTimeout(() => {
                                window.location.href = '{{ route("admin.users.index") }}';
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete user', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection