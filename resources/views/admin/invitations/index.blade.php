{{-- resources/views/admin/invitations/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Wedding Invitations')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Wedding Invitations</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Wedding Invitations</h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.templates.select') }}" class="btn btn-primary">
                    <i class="bi bi-plus fs-2"></i>
                    Create Wedding Invitation
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Filter Section -->
        <div class="d-flex flex-wrap gap-3 mb-6">
            <div class="position-relative">
                <input type="text" class="form-control w-250px" placeholder="Search by couple name..." id="searchInput">
                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                    <i class="bi bi-magnifier fs-3"></i>
                </span>
            </div>
            
            <select class="form-select w-150px" id="statusFilter">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
            </select>

            <button class="btn btn-light-primary" id="resetFilter">
                <i class="bi bi-arrows-circle"></i>
                Reset
            </button>
        </div>

        <!-- Invitations Table -->
        <div class="table-responsive">
            <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px">#</th>
                        <th class="min-w-200px">Couple / Event</th>
                        <th class="min-w-150px">Wedding Date</th>
                        <th class="min-w-150px">Template</th>
                        <th class="min-w-100px">Guests</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px">Created At</th>
                        <th class="min-w-150px">Actions</th>
                    </td>
                </thead>
                <tbody id="invitationsTableBody">
                    @forelse($invitations as $invitation)
                    @php
                        $statusBadge = match($invitation->status) {
                            'draft' => 'secondary',
                            'published' => 'success',
                            'archived' => 'danger',
                            default => 'secondary'
                        };
                        
                        $weddingDate = $invitation->getWeddingDateAttribute();
                        $guestsCount = $invitation->guests_count ?? $invitation->guests()->count();
                        $confirmedRsvp = $invitation->wishes()->where('attendance', 'yes')->count();
                    @endphp
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="bi bi-heart fs-2 text-primary"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}</span>
                                    <span class="text-muted fs-7">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($weddingDate)
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $weddingDate->format('d M Y') }}</span>
                                @if($invitation->akad_time)
                                <span class="text-muted fs-7">{{ \Carbon\Carbon::parse($invitation->akad_time)->format('H:i') }} WIB</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-light-primary fw-bold px-3 py-2">
                                {{ $invitation->template->name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $guestsCount }} Guests</span>
                                <span class="text-muted fs-7">{{ $confirmedRsvp }} confirmed RSVP</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $statusBadge }} fw-bold px-3 py-2">
                                {{ ucfirst($invitation->status) }}
                            </span>
                        </td>
                        <td>{{ $invitation->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.invitations.show', $invitation) }}" class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="tooltip" title="View Invitation">
                                    <i class="bi bi-eye fs-2"></i>
                                </a>
                                <a href="{{ route('admin.invitations.edit', $invitation) }}" class="btn btn-sm btn-icon btn-light-info" data-bs-toggle="tooltip" title="Edit Info">
                                    <i class="bi bi-pencil fs-2"></i>
                                </a>
                                <a href="{{ route('admin.invitations.customize-template', $invitation) }}" class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="tooltip" title="Customize Template">
                                    <i class="bi bi-palette"></i>
                                </a>
                                <a href="{{ route('admin.invitations.guests.index', $invitation) }}" class="btn btn-sm btn-icon btn-light-success" data-bs-toggle="tooltip" title="Manage Guests">
                                    <i class="bi bi-people fs-2"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-light-secondary" data-bs-toggle="dropdown" title="More Actions">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('invitation.show', $invitation->slug) }}" target="_blank">
                                                <i class="bi bi-eye me-2"></i>Preview Invitation
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item copy-link" data-link="{{ route('invitation.show', $invitation->slug) }}">
                                                <i class="bi bi-link me-2"></i>Copy Invitation Link
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.invitations.duplicate', $invitation) }}">
                                                <i class="bi bi-copy me-2"></i>Duplicate
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item text-danger delete-invitation" 
                                                    data-id="{{ $invitation->id }}" 
                                                    data-name="{{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}"
                                                    data-url="{{ route('admin.invitations.destroy', $invitation) }}">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-envelope fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">No Wedding Invitations Yet</h5>
                                <p class="text-muted mb-4">Create your first wedding invitation to get started.</p>
                                <a href="{{ route('admin.templates.select') }}" class="btn btn-primary">
                                    <i class="bi bi-plus"></i>
                                    Create Wedding Invitation
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invitations->count() > 0)
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
            <div class="text-muted">
                Showing {{ $invitations->firstItem() }} to {{ $invitations->lastItem() }} of {{ $invitations->total() }} wedding invitations
            </div>
            <div class="pagination">
                {{ $invitations->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterTable();
        }, 300);
    });
    
    // Filter by status
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        let searchText = document.getElementById('searchInput').value.toLowerCase();
        let status = document.getElementById('statusFilter').value;
        
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        
        // Skip if no data rows (empty state)
        if (rows.length === 1 && rows[0].querySelector('td[colspan]')) {
            return;
        }
        
        rows.forEach(row => {
            // Skip empty state row
            if (row.querySelector('td[colspan]')) return;
            
            let coupleName = row.cells[1]?.querySelector('.fw-bold')?.textContent.toLowerCase() || '';
            let rowStatus = row.cells[5]?.querySelector('.badge')?.textContent.toLowerCase() || '';
            
            let matchesSearch = !searchText || coupleName.includes(searchText);
            let matchesStatus = !status || rowStatus.includes(status);
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }
    
    // Reset all filters
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });
    
    // Copy invitation link
    document.querySelectorAll('.copy-link').forEach(button => {
        button.addEventListener('click', function() {
            const link = this.getAttribute('data-link');
            navigator.clipboard.writeText(link);
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Invitation link copied to clipboard',
                timer: 1500,
                showConfirmButton: false
            });
        });
    });
    
    // Delete invitation confirmation
    document.querySelectorAll('.delete-invitation').forEach(button => {
        button.addEventListener('click', function() {
            const invitationId = this.getAttribute('data-id');
            const invitationName = this.getAttribute('data-name');
            const deleteUrl = this.getAttribute('data-url'); // Ambil URL dari attribute
            
            Swal.fire({
                title: 'Delete Wedding Invitation?',
                html: `Are you sure you want to delete <strong>${invitationName}</strong>'s invitation?<br><small class="text-danger">This action cannot be undone. All guest data will be permanently deleted.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form for delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
@endsection