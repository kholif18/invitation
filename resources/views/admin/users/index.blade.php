{{-- resources/views/admin/users/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'User Management')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">User Management</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Users</h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex gap-3">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i>
                    Add User
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Filters -->
        <div class="d-flex flex-wrap gap-3 mb-6">
            <div class="position-relative">
                <input type="text" class="form-control w-250px" placeholder="Search users..." id="searchInput">
                <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
            
            <select class="form-select w-150px" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="banned">Banned</option>
            </select>
            
            <button class="btn btn-light-primary" id="resetFilter">
                <i class="bi bi-arrows-circle"></i>
                Reset
            </button>
            
            <button class="btn btn-light-success" onclick="exportUsers()">
                <i class="bi bi-download"></i>
                Export
            </button>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th class="min-w-200px">User</th>
                        <th class="min-w-150px">Status</th>
                        <th class="min-w-150px">Last Login</th>
                        <th class="min-w-150px">Created At</th>
                        <th class="min-w-150px">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" 
                                {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <div class="text-muted fs-7">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-muted fs-8">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'banned' => 'danger'
                                ];
                                $statusIcons = [
                                    'active' => 'check-circle',
                                    'inactive' => 'clock',
                                    'banned' => 'x-circle'
                                ];
                                $statusColor = $statusColors[$user->status] ?? 'secondary';
                                $statusIcon = $statusIcons[$user->status] ?? 'question-circle';
                            @endphp
                            <span class="badge badge-light-{{ $statusColor }}">
                                <i class="bi bi-{{ $statusIcon }} me-1"></i>
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>
                            @if($user->last_login_at)
                                <div class="fw-bold">{{ $user->last_login_at->format('d M Y') }}</div>
                                <div class="text-muted fs-7">{{ $user->last_login_at->format('H:i') }}</div>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $user->created_at->format('d M Y') }}</div>
                            <div class="text-muted fs-7">{{ $user->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                class="btn btn-sm btn-icon btn-light-primary" 
                                data-bs-toggle="tooltip" 
                                title="View">
                                    <i class="bi bi-eye fs-2"></i>
                                </a>
                                
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                class="btn btn-sm btn-icon btn-light-info" 
                                data-bs-toggle="tooltip" 
                                title="Edit">
                                    <i class="bi bi-pencil fs-2"></i>
                                </a>
                                
                                <button onclick="changePassword({{ $user->id }}, '{{ $user->name }}')"
                                        class="btn btn-sm btn-icon btn-light-warning" 
                                        data-bs-toggle="tooltip" 
                                        title="Change Password">
                                    <i class="bi bi-key fs-2"></i>
                                </button>
                                
                                @if(auth()->id() != $user->id)
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                        class="btn btn-sm btn-icon btn-light-danger" 
                                        data-bs-toggle="tooltip" 
                                        title="Delete">
                                    <i class="bi bi-trash fs-2"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                     </tr>
                    @empty
                     <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-people fs-3x text-muted"></i>
                            <p class="text-muted mt-2">No users found</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                Add Your First User
                            </a>
                        </td>
                     </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
            <div class="text-muted">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Bulk Actions</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Selected <span id="selectedCount">0</span> user(s)</p>
                <div class="d-flex gap-3">
                    <button class="btn btn-danger" onclick="bulkDelete()">
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Change Password</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="fw-bold mb-2">User</label>
                        <input type="text" class="form-control" id="passwordUserName" readonly>
                        <input type="hidden" id="passwordUserId">
                    </div>
                    <div class="mb-4">
                        <label class="required fw-bold mb-2">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="password" required>
                        <div class="form-text">Minimum 8 characters</div>
                    </div>
                    <div class="mb-4">
                        <label class="required fw-bold mb-2">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedUsers = [];

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox:not(:disabled)').forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Update selected count
    function updateSelectedCount() {
        selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
        document.getElementById('selectedCount').textContent = selectedUsers.length;
        
        if(selectedUsers.length > 0) {
            const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
            modal.show();
        }
    }

    document.querySelectorAll('.user-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });

    // Filter functionality
    function filterUsers() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        
        document.querySelectorAll('#usersTableBody tr').forEach(row => {
            if(row.querySelector('td[colspan]')) return;
            
            const text = row.textContent.toLowerCase();
            const statusCell = row.querySelector('.badge-light-success, .badge-light-warning, .badge-light-danger');
            const userStatus = statusCell ? statusCell.textContent.toLowerCase().trim() : '';
            
            let show = true;
            if(search && !text.includes(search)) show = false;
            if(status && userStatus !== status) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterUsers);
    document.getElementById('statusFilter').addEventListener('change', filterUsers);
    
    document.getElementById('resetFilter').addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        filterUsers();
    });

    // Delete user
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
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete user', 'error');
                    }
                });
            }
        });
    }

    // Change password
    function changePassword(id, name) {
        document.getElementById('passwordUserId').value = id;
        document.getElementById('passwordUserName').value = name;
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
        
        const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        modal.show();
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('passwordUserId').value;
        const password = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;
        
        if(password !== confirm) {
            Swal.fire('Error', 'Passwords do not match', 'error');
            return;
        }
        
        if(password.length < 8) {
            Swal.fire('Error', 'Password must be at least 8 characters', 'error');
            return;
        }
        
        $.ajax({
            url: `{{ url('admin/users') }}/${id}/change-password`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                password: password,
                password_confirmation: confirm
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire('Success!', response.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to change password', 'error');
            }
        });
    });

    // Bulk delete
    function bulkDelete() {
        if(selectedUsers.length === 0) return;
        
        Swal.fire({
            title: 'Delete Selected Users?',
            html: `This will delete <strong>${selectedUsers.length}</strong> user(s).<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.users.bulk-delete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedUsers
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete users', 'error');
                    }
                });
            }
        });
    }

    // Export users
    function exportUsers() {
        const status = document.getElementById('statusFilter').value;
        let url = '{{ route("admin.users.export") }}';
        
        if(status) {
            url += '?status=' + status;
        }
        
        window.location.href = url;
    }
</script>
@endpush
@endsection