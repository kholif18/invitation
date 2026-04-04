{{-- resources/views/admin/invitations/templates.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Wedding Invitation Templates')

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
<li class="breadcrumb-item text-dark">Templates</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Wedding Invitation Templates</h3>
            <div class="text-muted ms-4 mt-1">
                <span class="badge badge-light-primary" id="templateCount">0 Templates</span>
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-3">
                <button class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                    <i class="bi bi-cloud-upload"></i>
                    Upload Custom Template
                </button>
                <a href="{{ route('admin.invitations.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Alert Message -->
        <div class="alert alert-light-primary border-primary d-flex align-items-center mb-6">
            <i class="bi bi-info-circle fs-1 me-3 text-primary"></i>
            <div>
                <h5 class="mb-1 text-primary">Wedding Template Collection</h5>
                <p class="mb-0">Choose from our collection of beautiful wedding invitation templates. You can also upload your own custom templates.</p>
            </div>
        </div>

        <!-- Template Grid -->
        <div class="row g-6" id="templatesContainer">
            <!-- Templates will be loaded dynamically here -->
            <div class="col-12 text-center py-10" id="loadingTemplates">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading templates...</p>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h3 class="modal-title" id="previewTitle">Template Preview</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-5 text-center" id="previewContent">
                    <img id="previewImage" src="" class="img-fluid rounded shadow" style="max-height: 500px; object-fit: contain;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Close
                </button>
                <a href="#" class="btn btn-primary" id="useTemplateBtn">
                    <i class="bi bi-pencil"></i> Use This Template
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Upload Template Modal -->
<div class="modal fade" id="uploadTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload Custom Wedding Template</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadTemplateForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-6">
                        <label class="required fw-bold mb-2">Template Name</label>
                        <input type="text" class="form-control" name="template_name" placeholder="e.g., Rustic Wedding, Beach Theme, etc." required>
                        <div class="text-muted fs-7 mt-1">Give your template a descriptive name</div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="required fw-bold mb-2">Template Type</label>
                        <select class="form-select" name="template_type" required>
                            <option value="wedding">Wedding</option>
                        </select>
                        <div class="text-muted fs-7 mt-1">Template category (currently only wedding)</div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="required fw-bold mb-2">Preview Image/Screenshot</label>
                        <input type="file" class="form-control" name="preview_image" accept="image/*" required>
                        <div class="text-muted fs-7 mt-1">Upload a screenshot or preview image of the template (JPG, PNG, JPEG - Max 5MB)</div>
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="uploadPreview" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="required fw-bold mb-2">Template File</label>
                        <input type="file" class="form-control" name="template_file" accept=".html,.zip" required>
                        <div class="text-muted fs-7 mt-1">Supported formats: HTML or ZIP (Max 10MB)</div>
                        <div class="alert alert-info mt-2 mb-0">
                            <i class="bi bi-info-circle"></i> For HTML templates, ensure it's a complete HTML file with proper structure. For ZIP, include all assets (CSS, JS, images).
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <label class="fw-bold mb-2">Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Brief description of your template..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="bi bi-cloud-upload"></i> Upload Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Delete Template</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this template?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.template-image {
    height: 200px;
    object-fit: cover;
}
.border-dashed {
    border: 1px dashed var(--bs-gray-300);
}
</style>
@endpush

@push('scripts')
<script>
    let templates = [];
    let deleteTemplateId = null;
    
    // Load templates from localStorage (or API in production)
    function loadTemplates() {
        const storedTemplates = localStorage.getItem('wedding_templates');
        if(storedTemplates) {
            templates = JSON.parse(storedTemplates);
        } else {
            // Default templates
            templates = [
                {
                    id: 1,
                    name: 'Elegant Classic',
                    type: 'wedding',
                    preview_image: '/assets/images/templates/elegant-classic.jpg',
                    description: 'Traditional and formal design with elegant floral accents and timeless beauty.',
                    features: ['RSVP & Gift features', 'Floral design', 'Timeless elegance'],
                    is_default: true
                },
                {
                    id: 2,
                    name: 'Modern Minimalist',
                    type: 'wedding',
                    preview_image: '/assets/images/templates/modern-minimalist.jpg',
                    description: 'Clean and contemporary style with simple elegance and sophisticated design.',
                    features: ['Modern layout', 'Clean design', 'Animation effects'],
                    is_default: true
                },
                {
                    id: 3,
                    name: 'Floral Romance',
                    type: 'wedding',
                    preview_image: '/assets/images/templates/floral-romance.jpg',
                    description: 'Beautiful floral patterns with romantic touches and delicate watercolor effects.',
                    features: ['Floral background', 'Romantic theme', 'Watercolor effects'],
                    is_default: true
                },
                {
                    id: 4,
                    name: 'Premium Gold',
                    type: 'wedding',
                    preview_image: '/assets/images/templates/premium-gold.jpg',
                    description: 'Luxury golden accents with elegant design for an unforgettable celebration.',
                    features: ['Gold theme', 'Premium effects', 'Luxury design'],
                    is_default: true
                }
            ];
            saveTemplates();
        }
        renderTemplates();
    }
    
    // Save templates to localStorage
    function saveTemplates() {
        localStorage.setItem('wedding_templates', JSON.stringify(templates));
        updateTemplateCount();
    }
    
    // Update template count
    function updateTemplateCount() {
        document.getElementById('templateCount').textContent = templates.length + ' Template' + (templates.length !== 1 ? 's' : '');
    }
    
    // Render templates grid
    function renderTemplates() {
        const container = document.getElementById('templatesContainer');
        
        if(templates.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-10">
                    <i class="bi bi-folder2-open fs-5x text-muted mb-4"></i>
                    <h4>No Templates Available</h4>
                    <p class="text-muted mb-6">Upload your first wedding invitation template to get started.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                        <i class="bi bi-cloud-upload"></i>
                        Upload Template
                    </button>
                </div>
            `;
            return;
        }
        
        container.innerHTML = '';
        templates.forEach((template, index) => {
            const colors = ['primary', 'info', 'danger', 'warning', 'success', 'dark'];
            const color = colors[index % colors.length];
            
            const templateCard = `
                <div class="col-xl-3 col-md-6">
                    <div class="card card-hover h-100 border-hover-${color}">
                        <div class="card-body p-0">
                            <div class="position-relative">
                                <div class="bg-light-${color} h-200px rounded-top d-flex align-items-center justify-content-center overflow-hidden">
                                    ${template.preview_image && template.preview_image !== '/assets/images/templates/' ? 
                                        `<img src="${template.preview_image}" class="img-fluid w-100 h-100 object-fit-cover" style="object-fit: cover;" onerror="this.src='/assets/images/placeholder-template.jpg'">` :
                                        `<div class="text-center p-4">
                                            <i class="bi bi-${getTemplateIcon(template.name)} fs-4x text-${color} mb-3 d-block"></i>
                                            <i class="bi bi-heart fs-2x text-${color}"></i>
                                        </div>`
                                    }
                                </div>
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge badge-light-${color} fw-bold px-3 py-2">
                                        <i class="bi bi-heart fs-7 me-1"></i> Wedding
                                    </span>
                                </div>
                                ${!template.is_default ? `
                                <div class="position-absolute top-0 start-0 p-3">
                                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteTemplate(${template.id})" data-bs-toggle="tooltip" title="Delete Template">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </div>
                                ` : ''}
                            </div>
                            <div class="p-5">
                                <h4 class="mb-2">${template.name}</h4>
                                <p class="text-muted mb-3">${template.description || 'Beautiful wedding invitation design for your special day.'}</p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    ${template.features ? template.features.slice(0, 2).map(feature => `
                                        <span class="badge badge-light-${color}">
                                            <i class="bi bi-check-circle-fill fs-7 me-1"></i> ${feature}
                                        </span>
                                    `).join('') : ''}
                                </div>
                                <div class="d-flex gap-2 mt-4">
                                    <button class="btn btn-light-${color} w-50" onclick="previewTemplate(${template.id})">
                                        <i class="bi bi-eye"></i>
                                        Preview
                                    </button>
                                    <a href="{{ route('admin.invitations.create') }}?template=${encodeURIComponent(template.name)}" class="btn btn-${color} w-50">
                                        <i class="bi bi-pencil"></i>
                                        Use Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += templateCard;
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
    
    function getTemplateIcon(name) {
        const nameLower = name.toLowerCase();
        if(nameLower.includes('classic')) return 'flower1';
        if(nameLower.includes('modern')) return 'brush';
        if(nameLower.includes('floral')) return 'flower2';
        if(nameLower.includes('gold')) return 'crown';
        if(nameLower.includes('rustic')) return 'tree';
        if(nameLower.includes('beach')) return 'umbrella';
        if(nameLower.includes('vintage')) return 'clock';
        return 'heart';
    }
    
    // Preview template
    function previewTemplate(templateId) {
        const template = templates.find(t => t.id === templateId);
        if(template) {
            document.getElementById('previewTitle').innerHTML = `${template.name} Preview`;
            const previewImage = document.getElementById('previewImage');
            if(template.preview_image && template.preview_image !== '/assets/images/placeholder-template.jpg') {
                previewImage.src = template.preview_image;
                previewImage.style.display = 'block';
            } else {
                previewImage.style.display = 'none';
                document.getElementById('previewContent').innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-${getTemplateIcon(template.name)} fs-5x text-${['primary','info','danger','warning'][template.id % 4]} mb-3 d-block"></i>
                        <h3>${template.name}</h3>
                        <p class="text-muted">Preview image not available</p>
                        <div class="border rounded p-4 bg-white mt-3">
                            <p>Beautiful wedding invitation template with elegant design.</p>
                            <button class="btn btn-primary">Use Template</button>
                        </div>
                    </div>
                `;
            }
            
            const useBtn = document.getElementById('useTemplateBtn');
            useBtn.href = "{{ route('admin.invitations.create') }}?template=" + encodeURIComponent(template.name);
            
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }
    }
    
    // Delete template
    window.deleteTemplate = function(templateId) {
        const template = templates.find(t => t.id === templateId);
        if(template && !template.is_default) {
            deleteTemplateId = templateId;
            const modal = new bootstrap.Modal(document.getElementById('deleteTemplateModal'));
            modal.show();
        } else {
            Swal.fire('Cannot Delete', 'Default templates cannot be deleted', 'warning');
        }
    };
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if(deleteTemplateId) {
            const index = templates.findIndex(t => t.id === deleteTemplateId);
            if(index !== -1 && !templates[index].is_default) {
                templates.splice(index, 1);
                saveTemplates();
                renderTemplates();
                Swal.fire('Deleted!', 'Template has been deleted successfully.', 'success');
            }
            deleteTemplateId = null;
            bootstrap.Modal.getInstance(document.getElementById('deleteTemplateModal')).hide();
        }
    });
    
    // Image preview before upload
    document.querySelector('input[name="preview_image"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('imagePreview');
                const img = document.getElementById('uploadPreview');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').style.display = 'none';
        }
    });
    
    // Handle template upload
    document.getElementById('uploadTemplateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const templateName = formData.get('template_name');
        const previewImage = formData.get('preview_image');
        const templateFile = formData.get('template_file');
        const description = formData.get('description') || '';
        
        if(!templateName || !previewImage || !templateFile) {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
            return;
        }
        
        // Simulate upload - in production, this would be an AJAX call to server
        const reader = new FileReader();
        reader.onload = function(event) {
            const newTemplate = {
                id: Date.now(),
                name: templateName,
                type: 'wedding',
                preview_image: event.target.result,
                description: description,
                features: ['Custom design', 'Personalized'],
                is_default: false,
                file_name: templateFile.name
            };
            
            templates.push(newTemplate);
            saveTemplates();
            renderTemplates();
            
            Swal.fire({
                icon: 'success',
                title: 'Template Uploaded!',
                text: `${templateName} has been added to your collection.`,
                timer: 2000
            });
            
            document.getElementById('uploadTemplateForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            bootstrap.Modal.getInstance(document.getElementById('uploadTemplateModal')).hide();
        };
        
        reader.readAsDataURL(previewImage);
    });
    
    // Load templates on page load
    loadTemplates();
</script>
@endpush
@endsection