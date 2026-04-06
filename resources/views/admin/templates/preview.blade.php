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
        <div>
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
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-light" id="desktopView">
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
        <div class="preview-container" style="background: #f5f5f5; min-height: 600px;">
            <iframe src="{{ route('admin.templates.preview-iframe', $template) }}" 
                    id="previewIframe"
                    style="width: 100%; height: 80vh; border: none; background: white; transition: all 0.3s ease;">
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
                        <td>{{ $template->version }}</td>
                    </tr>
                    <tr>
                        <th>Author</th>
                        <td>
                            @if($template->author_url)
                                <a href="{{ $template->author_url }}" target="_blank">{{ $template->author }}</a>
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
                <h5>Available Options</h5>
                @if($template->config)
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
                @else
                    <p class="text-muted">No additional options available</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // View mode switching
    document.getElementById('desktopView').addEventListener('click', function() {
        const iframe = document.getElementById('previewIframe');
        iframe.style.width = '100%';
        iframe.style.maxWidth = '1200px';
        iframe.style.margin = '0 auto';
        iframe.style.display = 'block';
    });
    
    document.getElementById('tabletView').addEventListener('click', function() {
        const iframe = document.getElementById('previewIframe');
        iframe.style.width = '768px';
        iframe.style.maxWidth = '768px';
        iframe.style.margin = '0 auto';
        iframe.style.display = 'block';
    });
    
    document.getElementById('mobileView').addEventListener('click', function() {
        const iframe = document.getElementById('previewIframe');
        iframe.style.width = '375px';
        iframe.style.maxWidth = '375px';
        iframe.style.margin = '0 auto';
        iframe.style.display = 'block';
    });
</script>
@endpush
@endsection