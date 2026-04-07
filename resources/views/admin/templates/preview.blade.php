{{-- resources/views/admin/templates/preview.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Preview Template - ' . $template->name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.templates.index') }}" class="text-muted text-hover-primary">Templates</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Preview Template</li>
@endsection

@section('content')
<div class="mb-6">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>{{ $template->name }}</h2>
            <p class="text-muted">{{ $template->description }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit Template
            </a>
            <a href="{{ route('admin.invitations.create', ['template' => $template->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Use This Template
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Template Preview</h3>
        <div class="card-toolbar">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-light active" id="desktopView">
                    <i class="bi bi-pc-display"></i> Desktop
                </button>
                <button type="button" class="btn btn-sm btn-light" id="tabletView">
                    <i class="bi bi-tablet"></i> Tablet
                </button>
                <button type="button" class="btn btn-sm btn-light" id="mobileView">
                    <i class="bi bi-phone"></i> Mobile
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="preview-container" style="background: #f5f5f5; min-height: 600px; padding: 20px;">
            <iframe src="{{ route('admin.templates.preview-iframe', $template) }}" 
                    id="previewIframe"
                    style="width: 100%; height: 80vh; border: 1px solid #ddd; background: white; transition: all 0.3s ease; display: block; margin: 0 auto;">
            </iframe>
        </div>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header">
        <h3 class="card-title">Template Information</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Template Name</th>
                        <td>{{ $template->name }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td><span class="badge badge-light-info">{{ ucfirst($template->category) }}</span></td>
                    </tr>
                    <tr>
                        <th>Version</th>
                        <td>v{{ $template->version }}</td>
                    </tr>
                    <tr>
                        <th>Author</th>
                        <td>
                            @if($template->author_url)
                                <a href="{{ $template->author_url }}" target="_blank">{{ $template->author ?? '-' }}</a>
                            @else
                                {{ $template->author ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($template->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                            @if($template->is_default)
                                <span class="badge badge-primary">Default</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Thumbnail Preview</h5>
                <img src="{{ $template->thumbnail_url }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                
                <h5>Full Preview Image</h5>
                <img src="{{ $template->preview_url }}" class="img-fluid rounded" style="max-height: 300px; width: auto;">
            </div>
        </div>
        
        @if($template->config)
        <div class="row mt-4">
            <div class="col-12">
                <h5>Available Template Options</h5>
                <ul>
                    @if(isset($template->config['colors']))
                        <li><strong>Colors:</strong> {{ implode(', ', $template->config['colors']) }}</li>
                    @endif
                    @if(isset($template->config['fonts']))
                        <li><strong>Fonts:</strong> {{ implode(', ', $template->config['fonts']) }}</li>
                    @endif
                    @if(isset($template->config['layouts']))
                        <li><strong>Layouts:</strong> {{ implode(', ', $template->config['layouts']) }}</li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // View mode switching
    const desktopBtn = document.getElementById('desktopView');
    const tabletBtn = document.getElementById('tabletView');
    const mobileBtn = document.getElementById('mobileView');
    const iframe = document.getElementById('previewIframe');
    
    function setViewMode(width, isActive) {
        iframe.style.width = width;
        iframe.style.maxWidth = width;
        iframe.style.margin = '0 auto';
        iframe.style.display = 'block';
        
        // Update active state
        [desktopBtn, tabletBtn, mobileBtn].forEach(btn => btn.classList.remove('active'));
        isActive.classList.add('active');
    }
    
    desktopBtn.addEventListener('click', () => setViewMode('100%', desktopBtn));
    tabletBtn.addEventListener('click', () => setViewMode('768px', tabletBtn));
    mobileBtn.addEventListener('click', () => setViewMode('375px', mobileBtn));
</script>
@endpush
@endsection