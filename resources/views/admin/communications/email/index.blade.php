{{-- resources/views/admin/communications/email/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Email Campaigns')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.communications.index') }}" class="text-muted text-hover-primary">
        Communications
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Email Campaigns</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Email Campaigns</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('admin.communications.email.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i>
                New Campaign
            </a>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Filters -->
        <div class="d-flex flex-wrap gap-3 mb-6">
            <div class="position-relative">
                <input type="text" class="form-control w-250px" placeholder="Search campaigns..." id="searchCampaign">
                <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
            <select class="form-select w-150px" id="statusFilter">
                <option value="">All Status</option>
                <option value="Sent">Sent</option>
                <option value="Scheduled">Scheduled</option>
                <option value="Draft">Draft</option>
            </select>
            <button class="btn btn-light-primary" id="resetFilters">
                <i class="bi bi-arrows-circle"></i>
                Reset
            </button>
        </div>

        <!-- Campaigns Table -->
        <div class="table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th class="min-w-200px">Campaign Name</th>
                        <th class="min-w-200px">Subject</th>
                        <th class="min-w-100px">Recipients</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px">Sent Date</th>
                        <th class="min-w-100px">Open Rate</th>
                        <th class="min-w-150px">Actions</th>
                    </tr>
                </thead>
                <tbody id="campaignsTableBody">
                    <!-- Dynamic content -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
            <div class="text-muted" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
            <div class="pagination" id="paginationControls"></div>
        </div>
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
                <p>Selected <span id="selectedCount">0</span> campaigns</p>
                <div class="d-flex gap-3">
                    <button class="btn btn-success" onclick="bulkSend()">
                        <i class="bi bi-send"></i> Send Now
                    </button>
                    <button class="btn btn-danger" onclick="bulkDelete()">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let campaigns = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let selectedCampaigns = [];
    
    // Load campaigns
    function loadCampaigns() {
        $.ajax({
            url: '{{ route("admin.communications.email.index") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    campaigns = response.data;
                    renderTable();
                }
            }
        });
    }
    
    function renderTable() {
        let filteredData = getFilteredData();
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredData.slice(start, end);
        
        const tbody = document.getElementById('campaignsTableBody');
        
        if(paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-inbox fs-3x text-muted"></i>
                        <p class="text-muted mt-2">No campaigns found</p>
                        <a href="{{ route('admin.communications.email.create') }}" class="btn btn-primary btn-sm">
                            Create Your First Campaign
                        </a>
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        paginatedData.forEach(campaign => {
            const statusBadge = {
                'Sent': '<span class="badge badge-light-success">Sent</span>',
                'Scheduled': '<span class="badge badge-light-warning">Scheduled</span>',
                'Draft': '<span class="badge badge-light-secondary">Draft</span>'
            }[campaign.status];
            
            html += `
                <tr>
                    <td class="ps-4">
                        <input type="checkbox" class="form-check-input campaign-checkbox" value="${campaign.id}">
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-35px symbol-circle bg-light-primary me-2">
                                <i class="bi bi-envelope fs-4 text-primary"></i>
                            </div>
                            <span class="fw-bold">${campaign.name}</span>
                        </div>
                    </td>
                    <td>${campaign.subject}</td>
                    <td>${campaign.recipients}</td>
                    <td>${statusBadge}</td>
                    <td>${campaign.sent_date || '-'}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: ${campaign.open_rate}%"></div>
                            </div>
                            <span>${campaign.open_rate}%</span>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewCampaign(${campaign.id})"><i class="bi bi-eye me-2"></i>View Details</a></li>
                                <li><a class="dropdown-item" href="#" onclick="editCampaign(${campaign.id})"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#" onclick="duplicateCampaign(${campaign.id})"><i class="bi bi-copy me-2"></i>Duplicate</a></li>
                                ${campaign.status === 'Scheduled' ? '<li><a class="dropdown-item" href="#" onclick="cancelCampaign(' + campaign.id + ')"><i class="bi bi-x-circle me-2"></i>Cancel</a></li>' : ''}
                                ${campaign.status === 'Draft' ? '<li><a class="dropdown-item" href="#" onclick="sendCampaign(' + campaign.id + ')"><i class="bi bi-send me-2"></i>Send Now</a></li>' : ''}
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteCampaign(${campaign.id})"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Update pagination
        updatePagination(filteredData.length);
        
        // Attach checkbox events
        attachCheckboxEvents();
    }
    
    function getFilteredData() {
        let filtered = [...campaigns];
        
        const search = document.getElementById('searchCampaign').value.toLowerCase();
        if(search) {
            filtered = filtered.filter(c => 
                c.name.toLowerCase().includes(search) || 
                c.subject.toLowerCase().includes(search)
            );
        }
        
        const status = document.getElementById('statusFilter').value;
        if(status) {
            filtered = filtered.filter(c => c.status === status);
        }
        
        return filtered;
    }
    
    function updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        document.getElementById('paginationInfo').textContent = 
            `Showing ${Math.min((currentPage - 1) * itemsPerPage + 1, totalItems)} to ${Math.min(currentPage * itemsPerPage, totalItems)} of ${totalItems} entries`;
        
        let paginationHtml = '';
        if(currentPage > 1) {
            paginationHtml += `<a href="#" class="btn btn-icon btn-light btn-sm me-2" onclick="changePage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></a>`;
        }
        
        for(let i = 1; i <= Math.min(totalPages, 5); i++) {
            const activeClass = i === currentPage ? 'btn-primary' : 'btn-light';
            paginationHtml += `<a href="#" class="btn btn-icon ${activeClass} btn-sm me-2" onclick="changePage(${i})">${i}</a>`;
        }
        
        if(currentPage < totalPages) {
            paginationHtml += `<a href="#" class="btn btn-icon btn-light btn-sm" onclick="changePage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></a>`;
        }
        
        document.getElementById('paginationControls').innerHTML = paginationHtml;
    }
    
    function changePage(page) {
        currentPage = page;
        renderTable();
    }
    
    function attachCheckboxEvents() {
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.campaign-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });
        
        document.querySelectorAll('.campaign-checkbox').forEach(cb => {
            cb.addEventListener('change', () => updateSelectedCount());
        });
    }
    
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.campaign-checkbox:checked');
        selectedCampaigns = Array.from(checkboxes).map(cb => parseInt(cb.value));
        $('#selectedCount').text(selectedCampaigns.length);
    }
    
    function viewCampaign(id) {
        window.location.href = `{{ url('admin/communications/email') }}/${id}`;
    }
    
    function editCampaign(id) {
        window.location.href = `{{ url('admin/communications/email') }}/${id}/edit`;
    }
    
    function duplicateCampaign(id) {
        $.ajax({
            url: `{{ url('admin/communications/email') }}/${id}/duplicate`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    Swal.fire('Success', 'Campaign duplicated successfully', 'success');
                    loadCampaigns();
                }
            }
        });
    }
    
    function sendCampaign(id) {
        Swal.fire({
            title: 'Send Campaign?',
            text: 'Are you sure you want to send this campaign now?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send it!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/communications/email') }}/${id}/send`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Success', 'Campaign sent successfully', 'success');
                            loadCampaigns();
                        }
                    }
                });
            }
        });
    }
    
    function cancelCampaign(id) {
        Swal.fire({
            title: 'Cancel Campaign?',
            text: 'This will cancel the scheduled campaign.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/communications/email') }}/${id}/cancel`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Success', 'Campaign cancelled successfully', 'success');
                            loadCampaigns();
                        }
                    }
                });
            }
        });
    }
    
    function deleteCampaign(id) {
        Swal.fire({
            title: 'Delete Campaign?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/communications/email') }}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Deleted!', 'Campaign deleted successfully', 'success');
                            loadCampaigns();
                        }
                    }
                });
            }
        });
    }
    
    function bulkSend() {
        if(selectedCampaigns.length === 0) {
            Swal.fire('Warning', 'Please select at least one campaign', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Send Selected Campaigns?',
            text: `This will send ${selectedCampaigns.length} campaign(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send them!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.communications.email.bulk.send") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        campaign_ids: selectedCampaigns
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Success', response.message, 'success');
                            bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal')).hide();
                            loadCampaigns();
                        }
                    }
                });
            }
        });
    }
    
    function bulkDelete() {
        if(selectedCampaigns.length === 0) {
            Swal.fire('Warning', 'Please select at least one campaign', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Delete Selected Campaigns?',
            text: `This will delete ${selectedCampaigns.length} campaign(s). This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.communications.email.bulk.delete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        campaign_ids: selectedCampaigns
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal')).hide();
                            loadCampaigns();
                        }
                    }
                });
            }
        });
    }
    
    // Event listeners
    document.getElementById('searchCampaign').addEventListener('keyup', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('statusFilter').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('resetFilters').addEventListener('click', () => {
        document.getElementById('searchCampaign').value = '';
        document.getElementById('statusFilter').value = '';
        currentPage = 1;
        renderTable();
    });
    
    // Load initial data
    loadCampaigns();
</script>
@endpush
@endsection