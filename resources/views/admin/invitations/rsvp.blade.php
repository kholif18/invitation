{{-- resources/views/admin/invitations/rsvp.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'RSVP Management')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.invitations.index') }}" class="text-muted text-hover-primary">
        Wedding Invitations
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">RSVP Management</li>
@endsection

@section('content')
<div class="row g-6">
    <!-- Statistics Cards -->
    <div class="col-md-12">
        <div class="row g-6 mb-6">
            <div class="col-md-3">
                <div class="card card-border-hover border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-primary me-3">
                                <i class="bi bi-envelope-paper fs-2x text-primary"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">Total Invitations Sent</span>
                                <span class="fw-bold fs-2x" id="totalSent">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-border-hover border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-success me-3">
                                <i class="bi bi-check-circle fs-2x text-success"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">Confirmed Attendees</span>
                                <span class="fw-bold fs-2x" id="confirmedAttendees">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-border-hover border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-warning me-3">
                                <i class="bi bi-clock fs-2x text-warning"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">Pending Responses</span>
                                <span class="fw-bold fs-2x" id="pendingResponses">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-border-hover border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-danger me-3">
                                <i class="bi bi-x-circle fs-2x text-danger"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">Declined</span>
                                <span class="fw-bold fs-2x" id="declinedCount">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RSVP Statistics Charts -->
    <div class="col-xl-6">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">RSVP Statistics</h3>
                <div class="card-toolbar">
                    <select class="form-select form-select-sm w-150px" id="invitationSelect">
                        <option value="all">All Invitations</option>
                        <option value="1">John & Sarah's Wedding</option>
                        <option value="2">Michael & Emma's Wedding</option>
                        <option value="3">David & Lisa's Wedding</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <canvas id="rsvpChart" height="300"></canvas>
                    </div>
                </div>
                <div class="separator my-5"></div>
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Attendance Rate</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="progress h-20px">
                                    <div class="progress-bar bg-success" id="attendanceProgress" style="width: 0%"></div>
                                </div>
                            </div>
                            <span class="ms-3 fw-bold" id="attendanceRate">0%</span>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-check-circle-fill text-success me-2"></i> Confirmed</span>
                                <span id="confirmedPercent">0%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-clock-fill text-warning me-2"></i> Pending</span>
                                <span id="pendingPercent">0%</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="bi bi-x-circle-fill text-danger me-2"></i> Declined</span>
                                <span id="declinedPercent">0%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Guest Preferences</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-people-fill text-primary me-2"></i> Plus One Requests</span>
                                <span id="plusOneRequests">0</span>
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-primary" id="plusOneProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-car-front-fill text-info me-2"></i> Parking Needed</span>
                                <span id="parkingNeeded">0</span>
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" id="parkingProgress" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-egg-fried text-warning me-2"></i> Dietary Restrictions</span>
                                <span id="dietaryRestrictions">0</span>
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" id="dietaryProgress" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-6">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <button class="btn btn-light-primary w-100 py-4" onclick="sendReminders()">
                            <i class="bi bi-envelope-paper fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Send Reminders</span>
                            <span class="text-muted d-block fs-7">Send reminders to pending guests</span>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-light-success w-100 py-4" onclick="exportRSVP()">
                            <i class="bi bi-download fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Export RSVP Data</span>
                            <span class="text-muted d-block fs-7">Export to CSV/Excel</span>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-light-info w-100 py-4" onclick="generateReport()">
                            <i class="bi bi-file-text fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Generate Report</span>
                            <span class="text-muted d-block fs-7">Detailed RSVP report</span>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-light-warning w-100 py-4" onclick="manageSeating()">
                            <i class="bi bi-grid-3x3-gap-fill fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Seating Arrangement</span>
                            <span class="text-muted d-block fs-7">Manage table assignments</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RSVP Responses Table -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="fw-bold">RSVP Responses</h3>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex gap-3">
                        <div class="position-relative">
                            <input type="text" class="form-control w-200px" placeholder="Search guests..." id="searchGuest">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
                        </div>
                        <select class="form-select w-150px" id="rsvpStatusFilter">
                            <option value="">All Responses</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="pending">Pending</option>
                            <option value="declined">Declined</option>
                        </select>
                        <select class="form-select w-150px" id="invitationFilter">
                            <option value="all">All Invitations</option>
                            <option value="1">John & Sarah's Wedding</option>
                            <option value="2">Michael & Emma's Wedding</option>
                            <option value="3">David & Lisa's Wedding</option>
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
                    <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px">#</th>
                                <th class="min-w-200px">Guest Name</th>
                                <th class="min-w-150px">Email/Phone</th>
                                <th class="min-w-150px">Invitation</th>
                                <th class="min-w-100px">RSVP Status</th>
                                <th class="min-w-100px">Attendees</th>
                                <th class="min-w-150px">Preferences</th>
                                <th class="min-w-150px">Response Date</th>
                                <th class="min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rsvpTableBody">
                            <!-- Dynamic content will be loaded here -->
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
    </div>
</div>

<!-- RSVP Detail Modal -->
<div class="modal fade" id="rsvpDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">RSVP Details</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="rsvpDetailContent">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendMessage()">Send Message</button>
            </div>
        </div>
    </div>
</div>

<!-- Send Reminder Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Reminders</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="fw-bold mb-2">Send to:</label>
                    <select class="form-select" id="reminderTarget">
                        <option value="pending">All Pending Guests (<span id="pendingCount">0</span>)</option>
                        <option value="all">All Guests (<span id="allGuestsCount">0</span>)</option>
                        <option value="custom">Custom Selection</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="fw-bold mb-2">Message Template</label>
                    <textarea class="form-control" rows="5" id="reminderMessage">Dear [Guest Name],

We haven't received your RSVP response for [Event Name] yet. Please confirm your attendance by clicking the link below:

[RSVP Link]

Thank you!</textarea>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="sendViaEmail" checked>
                    <label class="form-check-label" for="sendViaEmail">Send via Email</label>
                </div>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="sendViaWhatsapp">
                    <label class="form-check-label" for="sendViaWhatsapp">Send via WhatsApp</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendReminderNow()">Send Reminders</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card-border-hover {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}
.card-border-hover:hover {
    border-color: var(--bs-primary);
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
}
.progress {
    background-color: var(--bs-gray-200);
    border-radius: 1rem;
}
.progress-bar {
    border-radius: 1rem;
}
.h-20px {
    height: 20px;
}
.w-200px {
    width: 200px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let rsvpData = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let chart = null;
    
    // Sample RSVP Data
    function loadRSVPData() {
        // In production, this would be an AJAX call to your server
        rsvpData = [
            { id: 1, guest_name: 'John Doe', email: 'john@example.com', phone: '+628123456789', invitation: 'John & Sarah\'s Wedding', status: 'confirmed', attendees: 2, plus_one: true, parking: true, dietary: 'Vegetarian', response_date: '2024-12-20', notes: 'Looking forward to it!' },
            { id: 2, guest_name: 'Jane Smith', email: 'jane@example.com', phone: '+628987654321', invitation: 'John & Sarah\'s Wedding', status: 'confirmed', attendees: 1, plus_one: false, parking: false, dietary: 'None', response_date: '2024-12-19', notes: '' },
            { id: 3, guest_name: 'Robert Johnson', email: 'robert@example.com', phone: '+628555555555', invitation: 'John & Sarah\'s Wedding', status: 'pending', attendees: 0, plus_one: false, parking: false, dietary: '', response_date: '', notes: '' },
            { id: 4, guest_name: 'Maria Garcia', email: 'maria@example.com', phone: '+628777777777', invitation: 'John & Sarah\'s Wedding', status: 'declined', attendees: 0, plus_one: false, parking: false, dietary: '', response_date: '2024-12-18', notes: 'Sorry, cannot attend' },
            { id: 5, guest_name: 'David Chen', email: 'david@example.com', phone: '+628999999999', invitation: 'John & Sarah\'s Wedding', status: 'confirmed', attendees: 3, plus_one: true, parking: true, dietary: 'Halal', response_date: '2024-12-17', notes: 'Will bring the kids' },
            { id: 6, guest_name: 'Sarah Wilson', email: 'sarah@example.com', phone: '+628111111111', invitation: 'Michael & Emma\'s Wedding', status: 'confirmed', attendees: 2, plus_one: true, parking: true, dietary: 'None', response_date: '2024-12-16', notes: '' },
            { id: 7, guest_name: 'James Brown', email: 'james@example.com', phone: '+628222222222', invitation: 'Michael & Emma\'s Wedding', status: 'pending', attendees: 0, plus_one: false, parking: false, dietary: '', response_date: '', notes: '' },
            { id: 8, guest_name: 'Lisa Anderson', email: 'lisa@example.com', phone: '+628333333333', invitation: 'David & Lisa\'s Wedding', status: 'confirmed', attendees: 2, plus_one: false, parking: true, dietary: 'Gluten Free', response_date: '2024-12-15', notes: 'Excited for the wedding!' },
        ];
        
        updateStatistics();
        renderTable();
        updateChart();
    }
    
    // Update Statistics
    function updateStatistics() {
        const totalSent = rsvpData.length;
        const confirmed = rsvpData.filter(r => r.status === 'confirmed').length;
        const pending = rsvpData.filter(r => r.status === 'pending').length;
        const declined = rsvpData.filter(r => r.status === 'declined').length;
        const confirmedAttendees = rsvpData.filter(r => r.status === 'confirmed').reduce((sum, r) => sum + r.attendees, 0);
        const plusOneRequests = rsvpData.filter(r => r.plus_one === true).length;
        const parkingNeeded = rsvpData.filter(r => r.parking === true).length;
        const dietaryRestrictions = rsvpData.filter(r => r.dietary && r.dietary !== 'None' && r.dietary !== '').length;
        
        document.getElementById('totalSent').textContent = totalSent;
        document.getElementById('confirmedAttendees').textContent = confirmedAttendees;
        document.getElementById('pendingResponses').textContent = pending;
        document.getElementById('declinedCount').textContent = declined;
        
        const attendanceRate = totalSent > 0 ? Math.round((confirmed / totalSent) * 100) : 0;
        document.getElementById('attendanceRate').textContent = attendanceRate + '%';
        document.getElementById('attendanceProgress').style.width = attendanceRate + '%';
        
        document.getElementById('confirmedPercent').textContent = totalSent > 0 ? Math.round((confirmed / totalSent) * 100) + '%' : '0%';
        document.getElementById('pendingPercent').textContent = totalSent > 0 ? Math.round((pending / totalSent) * 100) + '%' : '0%';
        document.getElementById('declinedPercent').textContent = totalSent > 0 ? Math.round((declined / totalSent) * 100) + '%' : '0%';
        
        document.getElementById('plusOneRequests').textContent = plusOneRequests;
        document.getElementById('plusOneProgress').style.width = totalSent > 0 ? (plusOneRequests / totalSent) * 100 + '%' : '0%';
        
        document.getElementById('parkingNeeded').textContent = parkingNeeded;
        document.getElementById('parkingProgress').style.width = totalSent > 0 ? (parkingNeeded / totalSent) * 100 + '%' : '0%';
        
        document.getElementById('dietaryRestrictions').textContent = dietaryRestrictions;
        document.getElementById('dietaryProgress').style.width = totalSent > 0 ? (dietaryRestrictions / totalSent) * 100 + '%' : '0%';
        
        document.getElementById('pendingCount').textContent = pending;
        document.getElementById('allGuestsCount').textContent = totalSent;
    }
    
    // Render Table
    function renderTable() {
        let filteredData = getFilteredData();
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredData.slice(start, end);
        
        const tbody = document.getElementById('rsvpTableBody');
        
        if(paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-inbox fs-3x text-muted"></i>
                        <p class="text-muted mt-2">No RSVP responses found</p>
                    </td>
                </tr>
            `;
            document.getElementById('paginationInfo').textContent = 'Showing 0 to 0 of 0 entries';
            document.getElementById('paginationControls').innerHTML = '';
            return;
        }
        
        let html = '';
        paginatedData.forEach((rsvp, index) => {
            const statusBadge = {
                confirmed: '<span class="badge badge-light-success fw-bold px-3 py-2"><i class="bi bi-check-circle"></i> Confirmed</span>',
                pending: '<span class="badge badge-light-warning fw-bold px-3 py-2"><i class="bi bi-clock"></i> Pending</span>',
                declined: '<span class="badge badge-light-danger fw-bold px-3 py-2"><i class="bi bi-x-circle"></i> Declined</span>'
            }[rsvp.status];
            
            const preferences = [];
            if(rsvp.plus_one) preferences.push('<i class="bi bi-people-fill text-primary" title="Plus One"></i>');
            if(rsvp.parking) preferences.push('<i class="bi bi-car-front-fill text-info" title="Parking"></i>');
            if(rsvp.dietary && rsvp.dietary !== 'None' && rsvp.dietary !== '') preferences.push('<i class="bi bi-egg-fried text-warning" title="Dietary: ' + rsvp.dietary + '"></i>');
            
            html += `
                <tr>
                    <td class="ps-4">${start + index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-35px symbol-circle me-2">
                                <div class="symbol-label bg-light-primary">
                                    <i class="bi bi-person fs-4 text-primary"></i>
                                </div>
                            </div>
                            <div>
                                <span class="fw-bold">${rsvp.guest_name}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>${rsvp.email}</div>
                        <div class="text-muted fs-7">${rsvp.phone}</div>
                    </td>
                    <td>${rsvp.invitation}</td>
                    <td>${statusBadge}</td>
                    <td class="fw-bold">${rsvp.attendees} guest${rsvp.attendees !== 1 ? 's' : ''}</td>
                    <td>
                        <div class="d-flex gap-2">
                            ${preferences.join('') || '<span class="text-muted">None</span>'}
                        </div>
                    </td>
                    <td>${rsvp.response_date ? new Date(rsvp.response_date).toLocaleDateString('id-ID') : '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-icon btn-light-primary" onclick="viewRSVPDetail(${rsvp.id})" data-bs-toggle="tooltip" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-light-info" onclick="editRSVP(${rsvp.id})" data-bs-toggle="tooltip" title="Edit Response">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-light-success" onclick="sendReminderToGuest(${rsvp.id})" data-bs-toggle="tooltip" title="Send Reminder">
                            <i class="bi bi-envelope"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Update pagination info
        const totalItems = filteredData.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        document.getElementById('paginationInfo').textContent = `Showing ${start + 1} to ${Math.min(end, totalItems)} of ${totalItems} entries`;
        
        // Render pagination controls
        let paginationHtml = '';
        if(currentPage > 1) {
            paginationHtml += `<a href="#" class="btn btn-icon btn-light btn-sm me-2" onclick="changePage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></a>`;
        }
        
        for(let i = 1; i <= Math.min(totalPages, 5); i++) {
            const activeClass = i === currentPage ? 'btn-primary' : 'btn-light';
            paginationHtml += `<a href="#" class="btn btn-icon ${activeClass} btn-sm me-2" onclick="changePage(${i})">${i}</a>`;
        }
        
        if(currentPage < totalPages) {
            paginationHtml += `<a href="#" class="btn btn-icon btn-light btn-sm ms-2" onclick="changePage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></a>`;
        }
        
        document.getElementById('paginationControls').innerHTML = paginationHtml;
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
    
    function getFilteredData() {
        let filtered = [...rsvpData];
        
        const search = document.getElementById('searchGuest').value.toLowerCase();
        if(search) {
            filtered = filtered.filter(r => r.guest_name.toLowerCase().includes(search) || r.email.toLowerCase().includes(search));
        }
        
        const status = document.getElementById('rsvpStatusFilter').value;
        if(status) {
            filtered = filtered.filter(r => r.status === status);
        }
        
        const invitation = document.getElementById('invitationFilter').value;
        if(invitation !== 'all') {
            filtered = filtered.filter(r => r.invitation.includes(invitation === '1' ? 'John' : invitation === '2' ? 'Michael' : 'David'));
        }
        
        return filtered;
    }
    
    function updateChart() {
        const confirmed = rsvpData.filter(r => r.status === 'confirmed').length;
        const pending = rsvpData.filter(r => r.status === 'pending').length;
        const declined = rsvpData.filter(r => r.status === 'declined').length;
        
        const ctx = document.getElementById('rsvpChart').getContext('2d');
        
        if(chart) {
            chart.destroy();
        }
        
        chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed (' + confirmed + ')', 'Pending (' + pending + ')', 'Declined (' + declined + ')'],
                datasets: [{
                    data: [confirmed, pending, declined],
                    backgroundColor: ['#50cd89', '#ffc700', '#f1416c'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
    
    function changePage(page) {
        currentPage = page;
        renderTable();
    }
    
    function viewRSVPDetail(id) {
        const rsvp = rsvpData.find(r => r.id === id);
        if(rsvp) {
            const modalBody = document.getElementById('rsvpDetailContent');
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h5>Guest Information</h5>
                        <p><strong>Name:</strong> ${rsvp.guest_name}</p>
                        <p><strong>Email:</strong> ${rsvp.email}</p>
                        <p><strong>Phone:</strong> ${rsvp.phone}</p>
                        <p><strong>Invitation:</strong> ${rsvp.invitation}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>RSVP Details</h5>
                        <p><strong>Status:</strong> ${rsvp.status.toUpperCase()}</p>
                        <p><strong>Attendees:</strong> ${rsvp.attendees}</p>
                        <p><strong>Plus One:</strong> ${rsvp.plus_one ? 'Yes' : 'No'}</p>
                        <p><strong>Parking Needed:</strong> ${rsvp.parking ? 'Yes' : 'No'}</p>
                        <p><strong>Dietary:</strong> ${rsvp.dietary || 'None'}</p>
                        <p><strong>Response Date:</strong> ${rsvp.response_date || 'Not responded yet'}</p>
                    </div>
                    ${rsvp.notes ? `
                    <div class="col-md-12 mt-3">
                        <h5>Additional Notes</h5>
                        <p>${rsvp.notes}</p>
                    </div>
                    ` : ''}
                </div>
            `;
            const modal = new bootstrap.Modal(document.getElementById('rsvpDetailModal'));
            modal.show();
        }
    }
    
    function editRSVP(id) {
        Swal.fire('Info', 'Edit functionality will be implemented here', 'info');
    }
    
    function sendReminderToGuest(id) {
        const rsvp = rsvpData.find(r => r.id === id);
        Swal.fire('Reminder Sent', `Reminder sent to ${rsvp.guest_name}`, 'success');
    }
    
    function sendReminders() {
        const modal = new bootstrap.Modal(document.getElementById('reminderModal'));
        modal.show();
    }
    
    function sendReminderNow() {
        Swal.fire('Success', 'Reminders have been sent to all pending guests', 'success');
        bootstrap.Modal.getInstance(document.getElementById('reminderModal')).hide();
    }
    
    function exportRSVP() {
        const filteredData = getFilteredData();
        const csv = [
            ['Guest Name', 'Email', 'Phone', 'Invitation', 'Status', 'Attendees', 'Plus One', 'Parking', 'Dietary', 'Response Date'],
            ...filteredData.map(r => [r.guest_name, r.email, r.phone, r.invitation, r.status, r.attendees, r.plus_one, r.parking, r.dietary, r.response_date])
        ].map(row => row.join(',')).join('\n');
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'rsvp_data.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        Swal.fire('Success', 'RSVP data exported successfully', 'success');
    }
    
    function generateReport() {
        Swal.fire('Report Generated', 'Detailed RSVP report has been generated and downloaded', 'success');
    }
    
    function manageSeating() {
        Swal.fire('Coming Soon', 'Seating arrangement feature will be available soon', 'info');
    }
    
    function sendMessage() {
        Swal.fire('Message Sent', 'Your message has been sent to the guest', 'success');
        bootstrap.Modal.getInstance(document.getElementById('rsvpDetailModal')).hide();
    }
    
    // Event Listeners
    document.getElementById('searchGuest').addEventListener('keyup', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('rsvpStatusFilter').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('invitationFilter').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('resetFilters').addEventListener('click', () => {
        document.getElementById('searchGuest').value = '';
        document.getElementById('rsvpStatusFilter').value = '';
        document.getElementById('invitationFilter').value = 'all';
        currentPage = 1;
        renderTable();
    });
    
    document.getElementById('invitationSelect').addEventListener('change', (e) => {
        document.getElementById('invitationFilter').value = e.target.value;
        currentPage = 1;
        renderTable();
        updateStatistics();
        updateChart();
    });
    
    // Load initial data
    loadRSVPData();
</script>
@endpush
@endsection