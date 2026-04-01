{{-- resources/views/admin/communications/templates/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Message Templates')

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
<li class="breadcrumb-item text-dark">Message Templates</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Message Templates</h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex gap-3">
                <button class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#importTemplateModal">
                    <i class="bi bi-upload"></i>
                    Import
                </button>
                <a href="{{ route('admin.communications.templates.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i>
                    New Template
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs nav-line-tabs mb-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#all_templates">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    All Templates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#email_templates">
                    <i class="bi bi-envelope"></i>
                    Email Templates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#whatsapp_templates">
                    <i class="bi bi-whatsapp"></i>
                    WhatsApp Templates
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- All Templates Tab -->
            <div class="tab-pane fade show active" id="all_templates">
                <div class="row g-6" id="allTemplatesContainer">
                    <!-- Dynamic content -->
                </div>
            </div>

            <!-- Email Templates Tab -->
            <div class="tab-pane fade" id="email_templates">
                <div class="row g-6" id="emailTemplatesContainer">
                    <!-- Dynamic content -->
                </div>
            </div>

            <!-- WhatsApp Templates Tab -->
            <div class="tab-pane fade" id="whatsapp_templates">
                <div class="row g-6" id="whatsappTemplatesContainer">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-6">
            <div class="text-muted" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
            <div class="pagination" id="paginationControls"></div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="previewTitle">Template Preview</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light p-4 rounded" id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="useTemplateFromPreview()">Use This Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Import Template Modal -->
<div class="modal fade" id="importTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Import Template</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="importTemplateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-6">
                        <label class="required fw-bold mb-2">Upload File</label>
                        <input type="file" class="form-control" name="file" accept=".json,.csv" required>
                        <div class="form-text mt-2">Supported formats: JSON, CSV (Max 5MB)</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> File should contain template data with name, type, and content.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let templates = [];
    let currentPage = 1;
    let itemsPerPage = 8;
    let currentTemplateId = null;
    
    // Load templates
    function loadTemplates() {
        $.ajax({
            url: '{{ route("admin.communications.templates.index") }}',
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    templates = response.data;
                    renderTemplates('all');
                    renderTemplates('email');
                    renderTemplates('whatsapp');
                }
            }
        });
    }
    
    function renderTemplates(type) {
        let filteredTemplates = templates;
        if(type === 'email') {
            filteredTemplates = templates.filter(t => t.type === 'email' || t.type === 'both');
        } else if(type === 'whatsapp') {
            filteredTemplates = templates.filter(t => t.type === 'whatsapp' || t.type === 'both');
        }
        
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredTemplates.slice(start, end);
        
        const containerId = type === 'all' ? 'allTemplatesContainer' : (type === 'email' ? 'emailTemplatesContainer' : 'whatsappTemplatesContainer');
        const container = document.getElementById(containerId);
        
        if(paginatedData.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-folder2-open fs-5x text-muted"></i>
                    <h5 class="mt-3">No templates found</h5>
                    <a href="{{ route('admin.communications.templates.create') }}" class="btn btn-primary btn-sm mt-2">
                        Create Your First Template
                    </a>
                </div>
            `;
            return;
        }
        
        let html = '';
        paginatedData.forEach(template => {
            const typeColor = {
                'email': 'primary',
                'whatsapp': 'success',
                'both': 'info'
            }[template.type];
            
            const typeIcon = {
                'email': 'envelope',
                'whatsapp': 'whatsapp',
                'both': 'chat-dots'
            }[template.type];
            
            html += `
                <div class="col-md-3">
                    <div class="card card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px symbol-circle bg-light-${typeColor} me-2">
                                        <i class="bi bi-${typeIcon} fs-2 text-${typeColor}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">${template.name}</h6>
                                        <span class="badge badge-light-${typeColor} fs-7">${template.type}</span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editTemplate(${template.id})"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="duplicateTemplate(${template.id})"><i class="bi bi-copy me-2"></i>Duplicate</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="previewTemplate(${template.id})"><i class="bi bi-eye me-2"></i>Preview</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteTemplate(${template.id})"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-muted mb-3">${template.content.substring(0, 100)}...</p>
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
        
        container.innerHTML = html;
        
        if(type === 'all') {
            updatePagination(filteredTemplates.length);
        }
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
        loadTemplates();
    }
    
    function previewTemplate(id) {
        const template = templates.find(t => t.id === id);
        if(template) {
            currentTemplateId = id;
            document.getElementById('previewTitle').innerHTML = `${template.name} Preview`;
            
            let previewHtml = template.content.replace(/\[Guest Name\]/g, '<strong>John Doe</strong>')
                .replace(/\[Event Name\]/g, '<strong>John & Sarah\'s Wedding</strong>')
                .replace(/\[Event Date\]/g, '<strong>December 25, 2024</strong>')
                .replace(/\[Event Time\]/g, '<strong>6:00 PM</strong>')
                .replace(/\[Event Location\]/g, '<strong>Grand Ballroom, Hotel Indonesia</strong>')
                .replace(/\[RSVP Link\]/g, '<a href="#">RSVP Here</a>')
                .replace(/\n/g, '<br>');
            
            document.getElementById('previewContent').innerHTML = previewHtml;
            
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }
    }
    
    function editTemplate(id) {
        window.location.href = `{{ url('admin/communications/templates') }}/${id}/edit`;
    }
    
    function duplicateTemplate(id) {
        $.ajax({
            url: `{{ url('admin/communications/templates') }}/${id}/duplicate`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    Swal.fire('Success', 'Template duplicated successfully', 'success');
                    loadTemplates();
                }
            }
        });
    }
    
    function deleteTemplate(id) {
        Swal.fire({
            title: 'Delete Template?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: `{{ url('admin/communications/templates') }}/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Deleted!', 'Template deleted successfully', 'success');
                            loadTemplates();
                        }
                    }
                });
            }
        });
    }
    
    function useTemplate(id) {
        const template = templates.find(t => t.id === id);
        if(template) {
            if(template.type === 'email' || template.type === 'both') {
                window.location.href = `{{ route('admin.communications.email.create') }}?template=${template.id}`;
            } else {
                window.location.href = `{{ route('admin.communications.whatsapp.create') }}?template=${template.id}`;
            }
        }
    }
    
    function useTemplateFromPreview() {
        if(currentTemplateId) {
            useTemplate(currentTemplateId);
            bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
        }
    }
    
    // Import template
    document.getElementById('importTemplateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.communications.templates.import") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    Swal.fire('Success', response.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('importTemplateModal')).hide();
                    loadTemplates();
                }
            }
        });
    });
    
    // Load initial data
    loadTemplates();
</script>
@endpush
@endsection