{{-- resources/views/admin/invitations/templates.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Invitation Templates')

@section('breadcrumb')
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
        Dashboard
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('invitations.index') }}" class="text-muted text-hover-primary">
        All Invitations
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Invitation Templates</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Invitation Templates</h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end gap-3">
                <button class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                    <i class="ki-duotone ki-file-up"></i>
                    Upload Custom Template
                </button>
                <a href="{{ route('invitations.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus"></i>
                    Create Invitation
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs nav-line-tabs mb-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#all_templates">
                    <i class="ki-duotone ki-element-plus fs-2"></i>
                    All Templates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#wedding_templates">
                    <i class="ki-duotone ki-heart fs-2"></i>
                    Wedding
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#birthday_templates">
                    <i class="ki-duotone ki-cake fs-2"></i>
                    Birthday
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#corporate_templates">
                    <i class="ki-duotone ki-building fs-2"></i>
                    Corporate
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#my_templates">
                    <i class="ki-duotone ki-folder fs-2"></i>
                    My Templates
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- All Templates Tab -->
            <div class="tab-pane fade show active" id="all_templates">
                <div class="row g-6">
                    @php
                        $templates = [
                            ['name' => 'Elegant Classic', 'type' => 'wedding', 'color' => 'primary', 'icon' => 'heart', 'preview' => 'classic'],
                            ['name' => 'Modern Minimalist', 'type' => 'wedding', 'color' => 'info', 'icon' => 'abstract', 'preview' => 'modern'],
                            ['name' => 'Floral Romance', 'type' => 'wedding', 'color' => 'danger', 'icon' => 'flower', 'preview' => 'floral'],
                            ['name' => 'Premium Gold', 'type' => 'wedding', 'color' => 'warning', 'icon' => 'crown', 'preview' => 'gold'],
                            ['name' => 'Birthday Bash', 'type' => 'birthday', 'color' => 'success', 'icon' => 'cake', 'preview' => 'birthday1'],
                            ['name' => 'Party Time', 'type' => 'birthday', 'color' => 'primary', 'icon' => 'balloon', 'preview' => 'birthday2'],
                            ['name' => 'Corporate Event', 'type' => 'corporate', 'color' => 'info', 'icon' => 'building', 'preview' => 'corporate1'],
                            ['name' => 'Business Seminar', 'type' => 'corporate', 'color' => 'dark', 'icon' => 'presentation', 'preview' => 'corporate2'],
                        ];
                    @endphp
                    
                    @foreach($templates as $template)
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-hover h-100">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    <div class="bg-light-{{ $template['color'] }} h-200px rounded-top d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-{{ $template['icon'] }} fs-3x text-{{ $template['color'] }}"></i>
                                    </div>
                                    <div class="position-absolute top-0 end-0 p-3">
                                        <span class="badge badge-light-{{ $template['color'] }} fw-bold px-3 py-2">
                                            {{ ucfirst($template['type']) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h4 class="mb-2">{{ $template['name'] }}</h4>
                                    <p class="text-muted mb-4">Beautiful and elegant design perfect for your special event.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-light-primary w-50" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="previewTemplate('{{ $template['name'] }}', '{{ $template['preview'] }}')">
                                            <i class="ki-duotone ki-eye"></i>
                                            Preview
                                        </button>
                                        <a href="{{ route('invitations.create', ['template' => $template['preview']]) }}" class="btn btn-primary w-50">
                                            <i class="ki-duotone ki-pencil"></i>
                                            Use
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Wedding Templates Tab -->
            <div class="tab-pane fade" id="wedding_templates">
                <div class="row g-6">
                    @foreach(array_slice($templates, 0, 4) as $template)
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-hover h-100">
                            <div class="card-body p-0">
                                <div class="bg-light-{{ $template['color'] }} h-200px rounded-top d-flex align-items-center justify-content-center">
                                    <i class="ki-duotone ki-{{ $template['icon'] }} fs-3x text-{{ $template['color'] }}"></i>
                                </div>
                                <div class="p-5">
                                    <h4 class="mb-2">{{ $template['name'] }}</h4>
                                    <p class="text-muted mb-4">Beautiful wedding invitation design.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-light-primary w-50" onclick="previewTemplate('{{ $template['name'] }}', '{{ $template['preview'] }}')">
                                            <i class="ki-duotone ki-eye"></i>
                                            Preview
                                        </button>
                                        <a href="{{ route('invitations.create', ['template' => $template['preview']]) }}" class="btn btn-primary w-50">
                                            <i class="ki-duotone ki-pencil"></i>
                                            Use
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Birthday Templates Tab -->
            <div class="tab-pane fade" id="birthday_templates">
                <div class="row g-6">
                    @foreach(array_slice($templates, 4, 2) as $template)
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-hover h-100">
                            <div class="card-body p-0">
                                <div class="bg-light-{{ $template['color'] }} h-200px rounded-top d-flex align-items-center justify-content-center">
                                    <i class="ki-duotone ki-{{ $template['icon'] }} fs-3x text-{{ $template['color'] }}"></i>
                                </div>
                                <div class="p-5">
                                    <h4 class="mb-2">{{ $template['name'] }}</h4>
                                    <p class="text-muted mb-4">Fun and colorful birthday designs.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-light-primary w-50" onclick="previewTemplate('{{ $template['name'] }}', '{{ $template['preview'] }}')">
                                            <i class="ki-duotone ki-eye"></i>
                                            Preview
                                        </button>
                                        <a href="{{ route('invitations.create', ['template' => $template['preview']]) }}" class="btn btn-primary w-50">
                                            <i class="ki-duotone ki-pencil"></i>
                                            Use
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Corporate Templates Tab -->
            <div class="tab-pane fade" id="corporate_templates">
                <div class="row g-6">
                    @foreach(array_slice($templates, 6, 2) as $template)
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-hover h-100">
                            <div class="card-body p-0">
                                <div class="bg-light-{{ $template['color'] }} h-200px rounded-top d-flex align-items-center justify-content-center">
                                    <i class="ki-duotone ki-{{ $template['icon'] }} fs-3x text-{{ $template['color'] }}"></i>
                                </div>
                                <div class="p-5">
                                    <h4 class="mb-2">{{ $template['name'] }}</h4>
                                    <p class="text-muted mb-4">Professional corporate event invitations.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-light-primary w-50" onclick="previewTemplate('{{ $template['name'] }}', '{{ $template['preview'] }}')">
                                            <i class="ki-duotone ki-eye"></i>
                                            Preview
                                        </button>
                                        <a href="{{ route('invitations.create', ['template' => $template['preview']]) }}" class="btn btn-primary w-50">
                                            <i class="ki-duotone ki-pencil"></i>
                                            Use
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- My Templates Tab -->
            <div class="tab-pane fade" id="my_templates">
                <div class="text-center py-10">
                    <i class="ki-duotone ki-folder fs-5x text-muted mb-4"></i>
                    <h4>No Custom Templates Yet</h4>
                    <p class="text-muted mb-6">Upload your own invitation templates to use them later.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                        <i class="ki-duotone ki-file-up"></i>
                        Upload Template
                    </button>
                </div>
            </div>
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
                <div class="bg-light p-5 rounded text-center" id="previewContent">
                    <!-- Preview content will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="useTemplateBtn">Use This Template</a>
            </div>
        </div>
    </div>
</div>

<!-- Upload Template Modal -->
<div class="modal fade" id="uploadTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload Custom Template</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-6">
                    <label class="required fw-bold mb-2">Template Name</label>
                    <input type="text" class="form-control" placeholder="Enter template name">
                </div>
                <div class="mb-6">
                    <label class="required fw-bold mb-2">Template Type</label>
                    <select class="form-select">
                        <option value="wedding">Wedding</option>
                        <option value="birthday">Birthday</option>
                        <option value="corporate">Corporate</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="required fw-bold mb-2">Upload File</label>
                    <input type="file" class="form-control" accept=".html,.zip,.json">
                    <div class="text-muted fs-7 mt-2">Supported formats: HTML, ZIP, JSON (Max 10MB)</div>
                </div>
                <div class="mb-0">
                    <label class="fw-bold mb-2">Preview Image</label>
                    <input type="file" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="uploadTemplate()">Upload Template</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card-hover {
    transition: all 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}
.h-200px {
    height: 200px;
}
</style>
@endpush

@push('scripts')
<script>
    let currentTemplate = '';
    
    function previewTemplate(name, type) {
        currentTemplate = name;
        document.getElementById('previewTitle').innerHTML = `${name} Preview`;
        
        let previewHtml = `
            <div class="text-center">
                <div class="mb-4">
                    <i class="ki-duotone ki-${type === 'classic' ? 'heart' : type === 'modern' ? 'abstract' : type === 'floral' ? 'flower' : 'crown'} fs-5x text-primary"></i>
                </div>
                <h2 class="mb-3">${name}</h2>
                <div class="border rounded p-4 bg-white">
                    <h4 class="mb-3">You're Invited!</h4>
                    <p class="mb-2">Join us for a special celebration</p>
                    <p class="text-muted">Date: Saturday, December 25, 2024</p>
                    <p class="text-muted">Time: 6:00 PM</p>
                    <p class="text-muted">Location: Grand Ballroom</p>
                    <div class="mt-4">
                        <button class="btn btn-primary">RSVP Now</button>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('previewContent').innerHTML = previewHtml;
        
        let useBtn = document.getElementById('useTemplateBtn');
        useBtn.href = "{{ route('invitations.create') }}?template=" + encodeURIComponent(name);
    }
    
    function uploadTemplate() {
        Swal.fire({
            icon: 'success',
            title: 'Template Uploaded!',
            text: 'Your custom template has been uploaded successfully.',
            timer: 2000
        }).then(() => {
            bootstrap.Modal.getInstance(document.getElementById('uploadTemplateModal')).hide();
        });
    }
</script>
@endpush
@endsection