{{-- resources/views/admin/communications/whatsapp/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'WhatsApp Messages')

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
<li class="breadcrumb-item text-dark">WhatsApp Messages</li>
@endsection

@section('content')
<div class="row g-6">
    <!-- WhatsApp Connection Status -->
    <div class="col-md-12">
        <div class="alert alert-success d-flex align-items-center" id="connectionStatus">
            <i class="bi bi-whatsapp fs-1 me-3"></i>
            <div>
                <h5 class="mb-1">WhatsApp Connected</h5>
                <p class="mb-0">Your WhatsApp Business account is connected and ready to send messages.</p>
            </div>
            <button class="btn btn-sm btn-light ms-auto" onclick="disconnectWhatsApp()">Disconnect</button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-md-12">
        <div class="row g-6 mb-6">
            <div class="col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-circle bg-success me-3">
                                <i class="bi bi-whatsapp fs-2 text-white"></i>
                            </div>
                            <div>
                                <span class="text-muted fs-7">Total Sent</span>
                                <h3 class="fw-bold mb-0" id="totalSent">1,234</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-circle bg-info me-3">
                                <i class="bi bi-check2-circle fs-2 text-white"></i>
                            </div>
                            <div>
                                <span class="text-muted fs-7">Delivered</span>
                                <h3 class="fw-bold mb-0" id="deliveredCount">1,156</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-circle bg-warning me-3">
                                <i class="bi bi-eye fs-2 text-white"></i>
                            </div>
                            <div>
                                <span class="text-muted fs-7">Read Rate</span>
                                <h3 class="fw-bold mb-0" id="readRate">92%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-circle bg-primary me-3">
                                <i class="bi bi-chat fs-2 text-white"></i>
                            </div>
                            <div>
                                <span class="text-muted fs-7">Response Rate</span>
                                <h3 class="fw-bold mb-0" id="responseRate">45%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="{{ route('admin.communications.whatsapp.create') }}" class="btn btn-success w-100 py-4">
                            <i class="bi bi-send fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Send New Message</span>
                            <span class="text-muted d-block fs-7">Send to individual or bulk</span>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-light-primary w-100 py-4" onclick="sendReminders()">
                            <i class="bi bi-bell fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Send Reminders</span>
                            <span class="text-muted d-block fs-7">Send to pending guests</span>
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-light-info w-100 py-4" onclick="exportLogs()">
                            <i class="bi bi-download fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Export Logs</span>
                            <span class="text-muted d-block fs-7">Export message history</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold">Message History</h3>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex gap-3">
                        <div class="position-relative">
                            <input type="text" class="form-control w-200px" placeholder="Search messages..." id="searchMessage">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                        </div>
                        <select class="form-select w-150px" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="delivered">Delivered</option>
                            <option value="sent">Sent</option>
                            <option value="failed">Failed</option>
                        </select>
                        <button class="btn btn-light-primary" id="resetFilters">
                            <i class="bi bi-arrows-circle"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4">Recipient</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Sent Date</th>
                                <th>Read Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="messagesTableBody">
                            <!-- Dynamic content -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
                    <div class="text-muted" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
                    <div class="pagination" id="paginationControls"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let messages = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    
    // Load messages
    function loadMessages() {
        $.ajax({
            url: '{{ route("admin.communications.whatsapp.index") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    messages = response.data;
                    renderTable();
                    
                    // Update stats
                    updateStats();
                }
            }
        });
    }
    
    function updateStats() {
        const total = messages.length;
        const delivered = messages.filter(m => m.status === 'delivered').length;
        const read = messages.filter(m => m.read_date).length;
        
        $('#totalSent').text(total);
        $('#deliveredCount').text(delivered);
        $('#readRate').text(total > 0 ? Math.round((read / total) * 100) : 0);
        $('#responseRate').text('45');
    }
    
    function renderTable() {
        let filteredData = getFilteredData();
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredData.slice(start, end);
        
        const tbody = document.getElementById('messagesTableBody');
        
        if(paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-inbox fs-3x text-muted"></i>
                        <p class="text-muted mt-2">No messages found</p>
                        <a href="{{ route('admin.communications.whatsapp.create') }}" class="btn btn-success btn-sm">
                            Send Your First Message
                        </a>
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        paginatedData.forEach(message => {
            const statusIcon = {
                'delivered': '<span class="badge badge-light-success"><i class="bi bi-check2-all"></i> Delivered</span>',
                'sent': '<span class="badge badge-light-warning"><i class="bi bi-check2"></i> Sent</span>',
                'failed': '<span class="badge badge-light-danger"><i class="bi bi-x-circle"></i> Failed</span>'
            }[message.status];
            
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-35px symbol-circle me-2">
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.recipient_name)}&background=25a35a&color=fff" alt="">
                            </div>
                            <div>
                                <div class="fw-bold">${message.recipient_name}</div>
                                <div class="text-muted fs-7">${message.phone}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 300px;">${message.message}</div>
                    </td>
                    <td>${statusIcon}</td>
                    <td>${message.sent_date || '-'}</td>
                    <td>${message.read_date || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-icon btn-light-primary" onclick="viewMessage(${message.id})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-light-success" onclick="resendMessage(${message.id})">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        updatePagination(filteredData.length);
    }
    
    function getFilteredData() {
        let filtered = [...messages];
        
        const search = document.getElementById('searchMessage').value.toLowerCase();
        if(search) {
            filtered = filtered.filter(m => 
                m.recipient_name.toLowerCase().includes(search) || 
                m.phone.includes(search)
            );
        }
        
        const status = document.getElementById('statusFilter').value;
        if(status) {
            filtered = filtered.filter(m => m.status === status);
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
    
    function viewMessage(id) {
        Swal.fire('Message Details', `Viewing message ID: ${id}`, 'info');
    }
    
    function resendMessage(id) {
        Swal.fire({
            title: 'Resend Message?',
            text: 'This will send the message again to the recipient.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, resend!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/communications/whatsapp') }}/${id}/resend`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Success', 'Message resent successfully', 'success');
                            loadMessages();
                        }
                    }
                });
            }
        });
    }
    
    function sendReminders() {
        Swal.fire({
            title: 'Send Reminders',
            text: 'Send reminders to all pending guests?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send!'
        }).then((result) => {
            if(result.isConfirmed) {
                Swal.fire('Success', 'Reminders sent to pending guests', 'success');
            }
        });
    }
    
    function exportLogs() {
        window.location.href = '{{ route("admin.communications.whatsapp.export.logs") }}';
    }
    
    function disconnectWhatsApp() {
        Swal.fire({
            title: 'Disconnect WhatsApp?',
            text: 'You will need to reconnect to send messages.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, disconnect'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.communications.whatsapp.disconnect") }}',
                    method: 'GET',
                    success: function(response) {
                        if(response.success) {
                            $('#connectionStatus').removeClass('alert-success').addClass('alert-warning');
                            $('#connectionStatus .h5').text('WhatsApp Disconnected');
                            $('#connectionStatus .mb-0').text('Connect your WhatsApp account to start sending messages.');
                            $('#connectionStatus button').hide();
                        }
                    }
                });
            }
        });
    }
    
    // Event listeners
    document.getElementById('searchMessage').addEventListener('keyup', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('statusFilter').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('resetFilters').addEventListener('click', () => {
        document.getElementById('searchMessage').value = '';
        document.getElementById('statusFilter').value = '';
        currentPage = 1;
        renderTable();
    });
    
    // Load initial data
    loadMessages();
</script>
@endpush
@endsection