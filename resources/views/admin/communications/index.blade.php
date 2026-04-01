{{-- resources/views/admin/communications/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Communications Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Communications</li>
@endsection

@section('content')
<div class="row g-6">
    <!-- Statistics Cards -->
    <div class="col-md-12">
        <div class="row g-6 mb-6">
            <div class="col-md-4">
                <div class="card card-border-hover border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-primary me-3">
                                <i class="bi bi-envelope-paper fs-2x text-primary"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">Email Campaigns</span>
                                <div class="d-flex align-items-baseline gap-3">
                                    <span class="fw-bold fs-2x" id="totalCampaigns">0</span>
                                    <span class="text-muted">Total</span>
                                </div>
                                <div class="d-flex gap-3 mt-1">
                                    <span class="badge badge-light-success">Sent: <span id="sentCampaigns">0</span></span>
                                    <span class="badge badge-light-warning">Scheduled: <span id="scheduledCampaigns">0</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-border-hover border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-success me-3">
                                <i class="bi bi-whatsapp fs-2x text-success"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">WhatsApp Messages</span>
                                <div class="d-flex align-items-baseline gap-3">
                                    <span class="fw-bold fs-2x" id="totalWhatsApp">0</span>
                                    <span class="text-muted">Sent</span>
                                </div>
                                <div class="d-flex gap-3 mt-1">
                                    <span class="badge badge-light-success">Delivered: <span id="deliveredMessages">0</span></span>
                                    <span class="badge badge-light-info">Read Rate: <span id="readRate">0</span>%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-border-hover border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px symbol-circle bg-light-info me-3">
                                <i class="bi bi-files fs-2x text-info"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="text-muted fs-7">Message Templates</span>
                                <div class="d-flex align-items-baseline gap-3">
                                    <span class="fw-bold fs-2x" id="totalTemplates">0</span>
                                    <span class="text-muted">Total</span>
                                </div>
                                <div class="d-flex gap-3 mt-1">
                                    <span class="badge badge-light-primary">Email: <span id="emailTemplates">0</span></span>
                                    <span class="badge badge-light-success">WhatsApp: <span id="whatsappTemplates">0</span></span>
                                </div>
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
                    <div class="col-md-3">
                        <a href="{{ route('admin.communications.email.create') }}" class="btn btn-light-primary w-100 py-4">
                            <i class="bi bi-envelope-plus fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Create Email Campaign</span>
                            <span class="text-muted d-block fs-7">Send bulk emails to guests</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.communications.whatsapp.create') }}" class="btn btn-light-success w-100 py-4">
                            <i class="bi bi-whatsapp fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Send WhatsApp</span>
                            <span class="text-muted d-block fs-7">Send instant WhatsApp messages</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.communications.templates.create') }}" class="btn btn-light-info w-100 py-4">
                            <i class="bi bi-plus-circle fs-2x d-block mb-2"></i>
                            <span class="fw-bold">Create Template</span>
                            <span class="text-muted d-block fs-7">Create reusable message templates</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-light-warning w-100 py-4" onclick="viewAnalytics()">
                            <i class="bi bi-graph-up fs-2x d-block mb-2"></i>
                            <span class="fw-bold">View Analytics</span>
                            <span class="text-muted d-block fs-7">See communication performance</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Email Campaigns -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Recent Email Campaigns</h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.communications.email.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th>Campaign Name</th>
                                <th>Status</th>
                                <th>Recipients</th>
                                <th>Open Rate</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="recentCampaigns">
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent WhatsApp Messages -->
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Recent WhatsApp Messages</h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.communications.whatsapp.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th>Recipient</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="recentWhatsApp">
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="spinner-border text-success" role="status"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Templates -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Popular Message Templates</h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.communications.templates.index') }}" class="btn btn-sm btn-light">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4" id="popularTemplates">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-info" role="status"></div>
                    </div>
                </div>
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
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
    // Load dashboard data
    function loadDashboardData() {
        // Load statistics
        $.ajax({
            url: '{{ route("admin.communications.dashboard") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    const data = response.data;
                    
                    // Update email stats
                    $('#totalCampaigns').text(data.email_campaigns.total);
                    $('#sentCampaigns').text(data.email_campaigns.sent);
                    $('#scheduledCampaigns').text(data.email_campaigns.scheduled);
                    
                    // Update WhatsApp stats
                    $('#totalWhatsApp').text(data.whatsapp.total_sent);
                    $('#deliveredMessages').text(data.whatsapp.delivered);
                    $('#readRate').text(data.whatsapp.read_rate);
                    
                    // Update template stats
                    $('#totalTemplates').text(data.templates.total);
                    $('#emailTemplates').text(data.templates.email);
                    $('#whatsappTemplates').text(data.templates.whatsapp);
                }
            }
        });
        
        // Load recent campaigns
        $.ajax({
            url: '{{ route("admin.communications.email.index") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    const campaigns = response.data.slice(0, 3);
                    let html = '';
                    campaigns.forEach(campaign => {
                        const statusBadge = {
                            'Sent': '<span class="badge badge-light-success">Sent</span>',
                            'Scheduled': '<span class="badge badge-light-warning">Scheduled</span>',
                            'Draft': '<span class="badge badge-light-secondary">Draft</span>'
                        }[campaign.status];
                        
                        html += `
                            <tr>
                                <td class="fw-bold">${campaign.name}</td>
                                <td>${statusBadge}</td>
                                <td>${campaign.recipients}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: ${campaign.open_rate}%"></div>
                                        </div>
                                        <span>${campaign.open_rate}%</span>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="viewCampaign(${campaign.id})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#recentCampaigns').html(html);
                }
            }
        });
        
        // Load recent WhatsApp messages
        $.ajax({
            url: '{{ route("admin.communications.whatsapp.index") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    const messages = response.data.slice(0, 3);
                    let html = '';
                    messages.forEach(message => {
                        const statusIcon = {
                            'delivered': '<i class="bi bi-check2-all text-success"></i>',
                            'sent': '<i class="bi bi-check2 text-warning"></i>',
                            'failed': '<i class="bi bi-x-circle text-danger"></i>'
                        }[message.status];
                        
                        html += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-30px symbol-circle me-2">
                                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.recipient_name)}&background=25a35a&color=fff" alt="">
                                        </div>
                                        <div>
                                            <div class="fw-bold">${message.recipient_name}</div>
                                            <div class="text-muted fs-7">${message.phone}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 250px;">${message.message}</div>
                                </td>
                                <td>${statusIcon}</td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-light-primary" onclick="viewMessage(${message.id})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#recentWhatsApp').html(html);
                }
            }
        });
        
        // Load popular templates
        $.ajax({
            url: '{{ route("admin.communications.templates.index") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    const templates = response.data.slice(0, 4);
                    let html = '';
                    templates.forEach(template => {
                        const typeColor = {
                            'email': 'primary',
                            'whatsapp': 'success',
                            'both': 'info'
                        }[template.type];
                        
                        html += `
                            <div class="col-md-3">
                                <div class="card card-hover">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px symbol-circle bg-light-${typeColor} me-2">
                                                    <i class="bi bi-${template.type === 'email' ? 'envelope' : (template.type === 'whatsapp' ? 'whatsapp' : 'chat')} fs-2 text-${typeColor}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">${template.name}</h6>
                                                    <span class="badge badge-light-${typeColor} fs-7">${template.type}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-3">${template.content.substring(0, 80)}...</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted fs-7">Used ${template.usage_count} times</span>
                                            <button class="btn btn-sm btn-light-${typeColor}" onclick="useTemplate(${template.id})">
                                                <i class="bi bi-send"></i> Use
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#popularTemplates').html(html);
                }
            }
        });
    }
    
    function viewCampaign(id) {
        window.location.href = `{{ url('admin/communications/email') }}/${id}`;
    }
    
    function viewMessage(id) {
        Swal.fire('Message Details', `Viewing message ID: ${id}`, 'info');
    }
    
    function useTemplate(id) {
        Swal.fire({
            title: 'Use Template',
            text: 'Do you want to use this template?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, use it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Success', 'Template loaded successfully', 'success');
            }
        });
    }
    
    function viewAnalytics() {
        Swal.fire('Analytics Dashboard', 'Coming soon! Full analytics dashboard will be available.', 'info');
    }
    
    // Load data on page load
    $(document).ready(function() {
        loadDashboardData();
    });
</script>
@endpush
@endsection