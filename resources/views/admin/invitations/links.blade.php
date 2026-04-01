{{-- resources/views/admin/invitations/links.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Invitation Links')

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
<li class="breadcrumb-item text-dark">Invitation Links</li>
@endsection

@section('content')
<div class="row g-6">
    <!-- Invitation Selection -->
    <div class="col-md-12">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Select Invitation</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" id="invitationSelect">
                            <option value="">-- Select Wedding Invitation --</option>
                            @foreach($invitations ?? [] as $invitation)
                                <option value="{{ $invitation->id }}" 
                                    {{ isset($currentInvitation) && $currentInvitation->id == $invitation->id ? 'selected' : '' }}>
                                    {{ $invitation->event_name }} - {{ $invitation->groom_name }} & {{ $invitation->bride_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" onclick="loadInvitationLinks()">
                            <i class="bi bi-arrow-repeat"></i> Load Links
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Type Selection -->
    <div class="col-md-12" id="linkConfigSection" style="display: none;">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Link Configuration</h3>
            </div>
            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-6">
                        <div class="form-check form-check-custom form-check-solid mb-4">
                            <input class="form-check-input" type="radio" name="linkType" id="personalizedLink" value="personalized" checked>
                            <label class="form-check-label fw-bold" for="personalizedLink">
                                <div class="d-flex flex-column">
                                    <span>Personalized Links</span>
                                    <span class="text-muted fs-7">Each guest gets a unique link with their name</span>
                                </div>
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="linkType" id="generalLink" value="general">
                            <label class="form-check-label fw-bold" for="generalLink">
                                <div class="d-flex flex-column">
                                    <span>General Link (One Link for All)</span>
                                    <span class="text-muted fs-7">Single link for all guests, no personalization</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Recommendation:</strong> Personalized links allow you to track individual guest responses and send reminders only to those who haven't RSVP'd.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div class="col-md-12 text-center" id="loadingIndicator" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Loading links...</p>
    </div>

    <!-- Personalized Links Section -->
    <div class="col-md-12" id="personalizedSection" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Personalized Invitation Links</h3>
                <div class="card-toolbar">
                    <button class="btn btn-primary btn-sm" onclick="generateAllLinks()">
                        <i class="bi bi-magic"></i>
                        Generate All Links
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4">
                                    <input type="checkbox" id="selectAllGuests">
                                </th>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Invitation Link</th>
                                <th>Status</th>
                                <th>Actions</th>
                              </tr>
                        </thead>
                        <tbody id="guestLinksTable">
                            <!-- Dynamic content from AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                <div class="d-flex justify-content-between align-items-center mt-6">
                    <div class="d-flex gap-3">
                        <select class="form-select w-150px" id="bulkAction">
                            <option value="">Bulk Actions</option>
                            <option value="send_all">Send Links to Selected</option>
                            <option value="copy_all">Copy Selected Links</option>
                            <option value="export_links">Export Links (CSV)</option>
                        </select>
                        <button class="btn btn-light-primary" onclick="executeBulkAction()">
                            Apply
                        </button>
                    </div>
                    <div class="text-muted" id="totalLinksCount">
                        <i class="bi bi-link"></i> 0 links
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General Link Section (Single Link) -->
    <div class="col-md-12" id="generalSection" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">General Invitation Link</h3>
                <div class="card-toolbar">
                    <button class="btn btn-primary btn-sm" onclick="generateGeneralLink()">
                        <i class="bi bi-magic"></i>
                        Generate Link
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="text-center mb-4">
                            <i class="bi bi-link-45deg fs-5x text-primary"></i>
                            <h4 class="mt-3">Share this link with all your guests</h4>
                            <p class="text-muted">One link for everyone. No personalization needed.</p>
                        </div>
                        
                        <div class="input-group mb-4" id="generalLinkContainer">
                            <input type="text" class="form-control form-control-lg" id="generalLinkInput" readonly placeholder="Generate link first">
                            <button class="btn btn-primary" onclick="copyGeneralLink()">
                                <i class="bi bi-copy"></i> Copy Link
                            </button>
                        </div>
                        
                        <div class="alert alert-success" id="generalLinkStats" style="display: none;">
                            <i class="bi bi-eye me-2"></i>
                            <span id="generalLinkViews">0</span> views
                        </div>
                        
                        <div class="d-flex gap-3 justify-content-center mt-4">
                            <button class="btn btn-success" onclick="shareWhatsApp()">
                                <i class="bi bi-whatsapp"></i> Share via WhatsApp
                            </button>
                            <button class="btn btn-primary" onclick="shareEmail()">
                                <i class="bi bi-envelope"></i> Share via Email
                            </button>
                            <button class="btn btn-secondary" onclick="copyGeneralLink()">
                                <i class="bi bi-copy"></i> Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Statistics -->
    <div class="col-md-12" id="statisticsSection" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Link Statistics</h3>
            </div>
            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-3">
                        <div class="bg-light-primary rounded p-4 text-center">
                            <i class="bi bi-link-45deg fs-2x text-primary"></i>
                            <h3 class="fw-bold mt-2 mb-0" id="totalLinksStat">0</h3>
                            <span class="text-muted">Total Links Generated</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light-success rounded p-4 text-center">
                            <i class="bi bi-eye fs-2x text-success"></i>
                            <h3 class="fw-bold mt-2 mb-0" id="totalViewsStat">0</h3>
                            <span class="text-muted">Total Views</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light-info rounded p-4 text-center">
                            <i class="bi bi-check-circle fs-2x text-info"></i>
                            <h3 class="fw-bold mt-2 mb-0" id="totalRSVPStat">0</h3>
                            <span class="text-muted">RSVP Received</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light-warning rounded p-4 text-center">
                            <i class="bi bi-clock fs-2x text-warning"></i>
                            <h3 class="fw-bold mt-2 mb-0" id="pendingRSVPStat">0</h3>
                            <span class="text-muted">Pending RSVP</span>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <canvas id="viewsChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Link Modal -->
<div class="modal fade" id="sendLinkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Send Invitation Link</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="fw-bold mb-2">Recipient</label>
                    <input type="text" class="form-control" id="recipientName" readonly>
                </div>
                <div class="mb-4">
                    <label class="fw-bold mb-2">Phone Number</label>
                    <input type="text" class="form-control" id="recipientPhone" readonly>
                </div>
                <div class="mb-4">
                    <label class="fw-bold mb-2">Message Preview</label>
                    <textarea class="form-control" rows="5" id="whatsappMessage"></textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="includeRSVPLink" checked>
                    <label class="form-check-label" for="includeRSVPLink">
                        Include RSVP link in message
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="sendWhatsAppNow()">
                    <i class="bi bi-whatsapp"></i> Send via WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table-responsive {
    overflow-x: auto;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let currentInvitationId = null;
    let currentGuest = null;
    let guestsData = [];
    let chart = null;
    
    // Load invitation links
    function loadInvitationLinks() {
        const invitationId = document.getElementById('invitationSelect').value;
        if(!invitationId) {
            Swal.fire('Warning', 'Please select an invitation', 'warning');
            return;
        }
        
        currentInvitationId = invitationId;
        
        // Show loading
        document.getElementById('loadingIndicator').style.display = 'block';
        document.getElementById('linkConfigSection').style.display = 'none';
        document.getElementById('personalizedSection').style.display = 'none';
        document.getElementById('generalSection').style.display = 'none';
        document.getElementById('statisticsSection').style.display = 'none';
        
        // Fetch links
        $.ajax({
            url: `{{ url('admin/invitations/links') }}/${invitationId}/data`,
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    const data = response.data;
                    guestsData = data.guests;
                    
                    // Show sections
                    document.getElementById('loadingIndicator').style.display = 'none';
                    document.getElementById('linkConfigSection').style.display = 'block';
                    document.getElementById('statisticsSection').style.display = 'block';
                    
                    // Update personalized links table
                    updatePersonalizedLinks(data);
                    
                    // Update general link section
                    updateGeneralLink(data.general_link);
                    
                    // Load statistics
                    loadStatistics(invitationId);
                }
            },
            error: function() {
                document.getElementById('loadingIndicator').style.display = 'none';
                Swal.fire('Error', 'Failed to load links', 'error');
            }
        });
    }
    
    function updatePersonalizedLinks(data) {
        const tbody = document.getElementById('guestLinksTable');
        const guests = data.guests;
        
        if(guests.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-people fs-3x text-muted"></i>
                        <p class="text-muted mt-2">No guests found for this invitation</p>
                    </td>
                </tr>
            `;
            document.getElementById('totalLinksCount').innerHTML = '<i class="bi bi-link"></i> 0 links';
            return;
        }
        
        let html = '';
        guests.forEach((guest, index) => {
            const statusBadge = guest.rsvp_status === 'confirmed' ? 
                '<span class="badge badge-light-success">Confirmed</span>' :
                (guest.rsvp_status === 'declined' ? 
                    '<span class="badge badge-light-danger">Declined</span>' : 
                    '<span class="badge badge-light-warning">Pending</span>');
            
            html += `
                <tr>
                    <td class="ps-4">
                        <input type="checkbox" class="form-check-input guest-checkbox" value="${guest.id}">
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-35px symbol-circle me-2">
                                <div class="symbol-label bg-light-primary">
                                    <i class="bi bi-person fs-4 text-primary"></i>
                                </div>
                            </div>
                            <span class="fw-bold">${escapeHtml(guest.name)}</span>
                        </div>
                    </td>
                    <td>${escapeHtml(guest.email || '-')}</td>
                    <td>${escapeHtml(guest.phone || '-')}</td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" 
                                   value="${guest.link || 'Not generated'}" 
                                   id="link${guest.id}" readonly>
                            ${guest.link ? `
                            <button class="btn btn-sm btn-light-primary" onclick="copyLink('link${guest.id}')">
                                <i class="bi bi-copy"></i>
                            </button>
                            ` : ''}
                        </div>
                    </td>
                    <td>${statusBadge}</td>
                    <td>
                        ${guest.link ? `
                        <button class="btn btn-sm btn-icon btn-light-success" onclick="sendLink(${guest.id}, '${escapeHtml(guest.name)}', '${escapeHtml(guest.phone)}', '${guest.link}')" title="Send Link">
                            <i class="bi bi-whatsapp"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-light-primary" onclick="copyLink('link${guest.id}')" title="Copy Link">
                            <i class="bi bi-copy"></i>
                        </button>
                        ` : ''}
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        const totalLinks = guests.filter(g => g.link).length;
        document.getElementById('totalLinksCount').innerHTML = `<i class="bi bi-link"></i> ${totalLinks} of ${guests.length} links generated`;
        
        // Add select all functionality
        document.getElementById('selectAllGuests').addEventListener('change', function() {
            document.querySelectorAll('.guest-checkbox').forEach(cb => cb.checked = this.checked);
        });
        
        document.getElementById('personalizedSection').style.display = 'block';
    }
    
    function updateGeneralLink(generalLink) {
        const linkInput = document.getElementById('generalLinkInput');
        const statsDiv = document.getElementById('generalLinkStats');
        
        if(generalLink) {
            linkInput.value = generalLink.link;
            document.getElementById('generalLinkViews').textContent = generalLink.views;
            statsDiv.style.display = 'block';
        } else {
            linkInput.value = 'Generate link first';
            statsDiv.style.display = 'none';
        }
        
        document.getElementById('generalSection').style.display = 'block';
    }
    
    function loadStatistics(invitationId) {
        $.ajax({
            url: `{{ url('admin/invitations/links/statistics') }}/${invitationId}`,
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    const data = response.data;
                    document.getElementById('totalLinksStat').textContent = data.total_links;
                    document.getElementById('totalViewsStat').textContent = data.total_views;
                    document.getElementById('totalRSVPStat').textContent = data.total_rsvp;
                    document.getElementById('pendingRSVPStat').textContent = data.pending_rsvp;
                    
                    // Update chart
                    updateChart(data.daily_views);
                }
            }
        });
    }
    
    function updateChart(dailyViews) {
        const ctx = document.getElementById('viewsChart').getContext('2d');
        
        if(chart) {
            chart.destroy();
        }
        
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyViews.map(d => d.date),
                datasets: [{
                    label: 'Daily Views',
                    data: dailyViews.map(d => d.views),
                    borderColor: '#009ef7',
                    backgroundColor: 'rgba(0, 158, 247, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }
    
    function generateAllLinks() {
        if(!currentInvitationId) return;
        
        const linkType = document.querySelector('input[name="linkType"]:checked').value;
        
        Swal.fire({
            title: 'Generate Links?',
            text: `This will create ${linkType === 'personalized' ? 'personalized links for all guests' : 'a general link for this invitation'}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, generate!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/invitations/links/generate') }}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        invitation_id: currentInvitationId,
                        type: linkType
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Success', response.message, 'success');
                            loadInvitationLinks();
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to generate links', 'error');
                    }
                });
            }
        });
    }
    
    function generateGeneralLink() {
        generateAllLinks();
    }
    
    function sendLink(guestId, guestName, guestPhone, guestLink) {
        currentGuest = {
            id: guestId,
            name: guestName,
            phone: guestPhone,
            link: guestLink
        };
        
        document.getElementById('recipientName').value = currentGuest.name;
        document.getElementById('recipientPhone').value = currentGuest.phone;
        
        let defaultMessage = `Hi [Guest Name],

You're invited to our wedding!

Click the link below to view the invitation and confirm your attendance:
[Invitation Link]

We look forward to celebrating with you!`;
        
        document.getElementById('whatsappMessage').value = defaultMessage;
        
        const modal = new bootstrap.Modal(document.getElementById('sendLinkModal'));
        modal.show();
    }
    
    function sendWhatsAppNow() {
        if(!currentGuest) return;
        
        let message = document.getElementById('whatsappMessage').value;
        message = message.replace('[Guest Name]', currentGuest.name);
        message = message.replace('[Invitation Link]', currentGuest.link);
        
        $.ajax({
            url: `{{ url('admin/invitations/links/send') }}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                guest_id: currentGuest.id,
                message: message
            },
            success: function(response) {
                if(response.success) {
                    window.open(response.data.whatsapp_url, '_blank');
                    Swal.fire('Success', 'WhatsApp opened. Click send to deliver the message.', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('sendLinkModal')).hide();
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to prepare WhatsApp message', 'error');
            }
        });
    }
    
    function copyLink(elementId) {
        const linkInput = document.getElementById(elementId);
        if(linkInput && linkInput.value !== 'Not generated') {
            linkInput.select();
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Link Copied!',
                timer: 1500,
                showConfirmButton: false
            });
        }
    }
    
    function copyGeneralLink() {
        const linkInput = document.getElementById('generalLinkInput');
        if(linkInput && linkInput.value !== 'Generate link first') {
            linkInput.select();
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Link Copied!',
                timer: 1500,
                showConfirmButton: false
            });
        }
    }
    
    function executeBulkAction() {
        const action = document.getElementById('bulkAction').value;
        const selectedGuests = Array.from(document.querySelectorAll('.guest-checkbox:checked')).map(cb => cb.value);
        
        if(!action) {
            Swal.fire('Warning', 'Please select an action', 'warning');
            return;
        }
        
        if(selectedGuests.length === 0) {
            Swal.fire('Warning', 'Please select at least one guest', 'warning');
            return;
        }
        
        if(action === 'send_all') {
            // Send to selected guests
            sendBulkLinks(selectedGuests);
        } else if(action === 'copy_all') {
            // Copy selected links
            const selectedLinks = guestsData
                .filter(g => selectedGuests.includes(g.id.toString()) && g.link)
                .map(g => `${g.name}: ${g.link}`);
            
            if(selectedLinks.length > 0) {
                navigator.clipboard.writeText(selectedLinks.join('\n'));
                Swal.fire('Success', `${selectedLinks.length} links copied to clipboard`, 'success');
            } else {
                Swal.fire('Warning', 'No links found for selected guests', 'warning');
            }
        } else if(action === 'export_links') {
            window.location.href = `{{ url('admin/invitations/links/export') }}/${currentInvitationId}`;
        }
    }
    
    function sendBulkLinks(guestIds) {
        const defaultMessage = `Hi [Guest Name],

You're invited to our wedding!

Click the link below to view the invitation and confirm your attendance:
[Invitation Link]

We look forward to celebrating with you!`;
        
        Swal.fire({
            title: 'Send to Selected Guests?',
            text: `This will prepare WhatsApp links for ${guestIds.length} guest(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Continue'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/invitations/links/bulk-send') }}`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        invitation_id: currentInvitationId,
                        guest_ids: guestIds,
                        message: defaultMessage
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                title: 'Messages Prepared',
                                html: `${response.data.sent} messages ready.<br>Click OK to open WhatsApp for each guest.`,
                                icon: 'success',
                                confirmButtonText: 'Open WhatsApp'
                            }).then(() => {
                                // Open first WhatsApp link
                                if(response.data.results.length > 0) {
                                    window.open(response.data.results[0].whatsapp_url, '_blank');
                                    Swal.fire('Info', `Opening WhatsApp for ${response.data.results.length} guest(s). You'll need to send each one manually.`, 'info');
                                }
                            });
                        }
                    }
                });
            }
        });
    }
    
    function shareWhatsApp() {
        const link = document.getElementById('generalLinkInput').value;
        if(link && link !== 'Generate link first') {
            const message = `Hi! You're invited to our wedding. View invitation here: ${link}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }
    }
    
    function shareEmail() {
        const link = document.getElementById('generalLinkInput').value;
        if(link && link !== 'Generate link first') {
            const subject = encodeURIComponent('Wedding Invitation');
            const body = encodeURIComponent(`Hi,\n\nYou're invited to our wedding!\n\nView invitation: ${link}\n\nWe look forward to celebrating with you!`);
            window.location.href = `mailto:?subject=${subject}&body=${body}`;
        }
    }
    
    function escapeHtml(text) {
        if(!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Load invitation if already selected in URL
        const urlParams = new URLSearchParams(window.location.search);
        const invitationId = urlParams.get('invitation');
        if(invitationId) {
            document.getElementById('invitationSelect').value = invitationId;
            loadInvitationLinks();
        }
    });
</script>
@endpush
@endsection