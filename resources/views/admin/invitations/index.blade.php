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
                <a href="{{ route('admin.invitations.templates') }}" class="btn btn-primary">
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
                <input type="text" class="form-control w-250px" placeholder="Search by couple name or event..." id="searchInput">
                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                    <i class="bi bi-magnifier fs-3"></i>
                </span>
            </div>
            
            <select class="form-select w-150px" id="statusFilter">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
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
                    </tr>
                </thead>
                <tbody id="invitationsTableBody">
                    @for($i = 1; $i <= 10; $i++)
                    @php
                        $groomNames = ['John', 'Michael', 'David', 'James', 'Robert'];
                        $brideNames = ['Sarah', 'Emma', 'Lisa', 'Anna', 'Maria'];
                        $groom = $groomNames[array_rand($groomNames)];
                        $bride = $brideNames[array_rand($brideNames)];
                        $templates = [
                            'elegant' => 'Elegant Classic',
                            'modern' => 'Modern Minimalist',
                            'floral' => 'Floral Romance',
                            'premium' => 'Premium Gold'
                        ];
                        $templateKey = array_rand($templates);
                        $template = $templates[$templateKey];
                        $templateBadgeClass = match($templateKey) {
                            'elegant' => 'primary',
                            'modern' => 'info',
                            'floral' => 'success',
                            'premium' => 'warning',
                            default => 'secondary'
                        };
                        $statuses = ['draft', 'sent', 'pending', 'cancelled'];
                        $status = $statuses[array_rand($statuses)];
                        $statusBadge = match($status) {
                            'draft' => 'secondary',
                            'sent' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <tr>
                        <td class="ps-4">{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <div class="symbol-label bg-light-{{ $templateBadgeClass }}">
                                        <i class="bi bi-heart fs-2 text-{{ $templateBadgeClass }}"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $groom }} & {{ $bride }}</span>
                                    <span class="text-muted fs-7">{{ $groom }} & {{ $bride }}'s Wedding</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ \Carbon\Carbon::now()->addDays(rand(5, 60))->format('d M Y') }}</span>
                                <span class="text-muted fs-7">{{ \Carbon\Carbon::now()->addDays(rand(5, 60))->format('H:i') }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $templateBadgeClass }} fw-bold px-3 py-2">{{ $template }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ rand(50, 500) }} Guests</span>
                                <span class="text-muted fs-7">{{ rand(0, 100) }} confirmed RSVP</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light-{{ $statusBadge }} fw-bold px-3 py-2">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::now()->subDays(rand(1, 30))->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="tooltip" title="View Invitation">
                                    <i class="bi bi-eye fs-2"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-icon btn-light-info" data-bs-toggle="tooltip" title="Edit Invitation">
                                    <i class="bi bi-pencil fs-2"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-icon btn-light-success" data-bs-toggle="tooltip" title="Preview">
                                    <i class="bi bi-file-text fs-2"></i>
                                </a>
                                <button class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="tooltip" title="Delete" onclick="confirmDelete({{ $i }})">
                                    <i class="bi bi-trash fs-2"></i>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-light-secondary" data-bs-toggle="dropdown" title="More Actions">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-send me-2"></i>Send Invitation</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Export Guest List</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-copy me-2"></i>Duplicate</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-archive me-2"></i>Archive</a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
            <div class="text-muted">Showing 1 to 10 of 50 wedding invitations</div>
            <div class="pagination">
                <a href="#" class="btn btn-icon btn-light btn-sm me-2">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <a href="#" class="btn btn-icon btn-primary btn-sm me-2">1</a>
                <a href="#" class="btn btn-icon btn-light btn-sm me-2">2</a>
                <a href="#" class="btn btn-icon btn-light btn-sm me-2">3</a>
                <a href="#" class="btn btn-icon btn-light btn-sm">4</a>
                <a href="#" class="btn btn-icon btn-light btn-sm ms-2">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
    
    // Filter by status
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    
    function filterTable() {
        let status = document.getElementById('statusFilter').value.toLowerCase();
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        
        rows.forEach(row => {
            let statusCell = row.cells[5]?.textContent.toLowerCase() || '';
            let templateCell = row.cells[3]?.textContent.toLowerCase() || '';
            let dateCell = row.cells[2]?.querySelector('.fw-bold')?.textContent || '';
            
            let statusMatch = !status || statusCell.includes(status);
            let templateMatch = !template || templateCell.includes(template);
            let dateMatch = !date || dateCell.includes(date);
            
            row.style.display = (statusMatch && templateMatch && dateMatch) ? '' : 'none';
        });
    }
    
    // Reset all filters
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        rows.forEach(row => row.style.display = '');
    });
    
    // Delete confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Wedding Invitation?',
            text: "This action cannot be undone. All guest data will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would normally make an AJAX call to delete the invitation
                Swal.fire(
                    'Deleted!',
                    'Wedding invitation has been deleted successfully.',
                    'success'
                );
            }
        });
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
@endsection