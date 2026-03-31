{{-- resources/views/admin/invitations/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'All Invitations')

@section('breadcrumb')
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
        Dashboard
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">All Invitations</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">All Invitations</h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('invitations.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Create Invitation
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Filter Section -->
        <div class="d-flex flex-wrap gap-3 mb-6">
            <div class="position-relative">
                <input type="text" class="form-control w-250px" placeholder="Search invitations..." id="searchInput">
                <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                    <i class="ki-duotone ki-magnifier fs-3"></i>
                </span>
            </div>
            
            <select class="form-select w-150px" id="statusFilter">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
            </select>
            
            <select class="form-select w-150px" id="eventTypeFilter">
                <option value="">All Event Types</option>
                <option value="wedding">Wedding</option>
                <option value="birthday">Birthday</option>
                <option value="corporate">Corporate</option>
                <option value="graduation">Graduation</option>
            </select>
            
            <button class="btn btn-light-primary" id="resetFilter">
                <i class="ki-duotone ki-arrows-circle"></i>
                Reset
            </button>
        </div>

        <!-- Invitations Table -->
        <div class="table-responsive">
            <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px">#</th>
                        <th class="min-w-200px">Event Name</th>
                        <th class="min-w-150px">Event Date</th>
                        <th class="min-w-150px">Event Type</th>
                        <th class="min-w-100px">Guests</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px">Created At</th>
                        <th class="min-w-150px">Actions</th>
                    </tr>
                </thead>
                <tbody id="invitationsTableBody">
                    @for($i = 1; $i <= 10; $i++)
                    <tr>
                        <td class="ps-4">{{ $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <div class="symbol-label bg-light-{{ ['primary', 'success', 'warning', 'info', 'danger'][array_rand(['primary','success','warning','info','danger'])] }}">
                                        <i class="ki-duotone ki-calendar fs-2 text-{{ ['primary', 'success', 'warning', 'info', 'danger'][array_rand(['primary','success','warning','info','danger'])] }}"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Wedding Invitation #{{ $i }}</span>
                                    <span class="text-muted fs-7">ID: INV-2024-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::now()->addDays(rand(5, 60))->format('d M Y, H:i') }}</td>
                        <td>
                            @php
                                $types = ['Wedding', 'Birthday', 'Corporate', 'Graduation'];
                                $type = $types[array_rand($types)];
                                $badgeClass = match($type) {
                                    'Wedding' => 'primary',
                                    'Birthday' => 'warning',
                                    'Corporate' => 'info',
                                    default => 'success'
                                };
                            @endphp
                            <span class="badge badge-light-{{ $badgeClass }} fw-bold px-3 py-2">{{ $type }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ rand(10, 250) }} Guests</span>
                                <span class="text-muted fs-7">{{ rand(0, 50) }} confirmed</span>
                            </div>
                        </td>
                        <td>
                            @php
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
                            <span class="badge badge-light-{{ $statusBadge }} fw-bold px-3 py-2">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::now()->subDays(rand(1, 30))->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="tooltip" title="View">
                                    <i class="ki-duotone ki-eye fs-2"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-icon btn-light-info" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ki-duotone ki-pencil fs-2"></i>
                                </a>
                                <button class="btn btn-sm btn-icon btn-light-danger" data-bs-toggle="tooltip" title="Delete" onclick="confirmDelete({{ $i }})">
                                    <i class="ki-duotone ki-trash fs-2"></i>
                                </button>
                                <a href="#" class="btn btn-sm btn-icon btn-light-success" data-bs-toggle="tooltip" title="Send">
                                    <i class="ki-duotone ki-send fs-2"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
            <div class="text-muted">Showing 1 to 10 of 50 entries</div>
            <div class="pagination">
                <a href="#" class="btn btn-icon btn-light btn-sm me-2">
                    <i class="ki-duotone ki-left"></i>
                </a>
                <a href="#" class="btn btn-icon btn-primary btn-sm me-2">1</a>
                <a href="#" class="btn btn-icon btn-light btn-sm me-2">2</a>
                <a href="#" class="btn btn-icon btn-light btn-sm me-2">3</a>
                <a href="#" class="btn btn-icon btn-light btn-sm">4</a>
                <a href="#" class="btn btn-icon btn-light btn-sm ms-2">
                    <i class="ki-duotone ki-right"></i>
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
    
    document.getElementById('eventTypeFilter').addEventListener('change', function() {
        filterTable();
    });
    
    function filterTable() {
        let status = document.getElementById('statusFilter').value.toLowerCase();
        let eventType = document.getElementById('eventTypeFilter').value.toLowerCase();
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        
        rows.forEach(row => {
            let statusCell = row.cells[5]?.textContent.toLowerCase() || '';
            let eventTypeCell = row.cells[3]?.textContent.toLowerCase() || '';
            
            let statusMatch = !status || statusCell.includes(status);
            let typeMatch = !eventType || eventTypeCell.includes(eventType);
            
            row.style.display = (statusMatch && typeMatch) ? '' : 'none';
        });
    }
    
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('eventTypeFilter').value = '';
        let rows = document.querySelectorAll('#invitationsTableBody tr');
        rows.forEach(row => row.style.display = '');
    });
    
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Invitation has been deleted.',
                    'success'
                );
            }
        });
    }
</script>
@endpush
@endsection