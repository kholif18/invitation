{{-- resources/views/admin/templates/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Upload New Template')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.templates.index') }}" class="text-muted text-hover-primary">Templates</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Upload New Template</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Upload New Template</h3>
        <div class="card-toolbar">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back to Templates
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data" id="templateForm">
            @csrf
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Template Structure Requirements:</strong>
                <ul class="mt-2 mb-0">
                    <li>Zip file should contain a <code>views/</code> folder with blade templates</li>
                    <li>Main template file should be <code>views/index.blade.php</code></li>
                    <li>Optional <code>assets/</code> folder for CSS, JS, audio, and images</li>
                    <li>Template will be accessible at: <code>templates.{slug}.index</code></li>
                </ul>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Template Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" 
                           placeholder="e.g., Elegant Gold Theme" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Category</label>
                    <select class="form-control @error('category') is-invalid @enderror" name="category" required>
                        <option value="">Select Category</option>
                        <option value="classic" {{ old('category') == 'classic' ? 'selected' : '' }}>Classic</option>
                        <option value="modern" {{ old('category') == 'modern' ? 'selected' : '' }}>Modern</option>
                        <option value="elegant" {{ old('category') == 'elegant' ? 'selected' : '' }}>Elegant</option>
                        <option value="jawa" {{ old('category') == 'jawa' ? 'selected' : '' }}>Jawa</option>
                        <option value="minimalis" {{ old('category') == 'minimalis' ? 'selected' : '' }}>Minimalis</option>
                        <option value="custom" {{ old('category') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              name="description" rows="3" 
                              placeholder="Template description">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Thumbnail Image</label>
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                           name="thumbnail" accept="image/*" id="thumbnailInput">
                    <div class="form-text">Recommended size: 400x300px (Max 2MB)</div>
                    <div id="thumbnailPreview" class="mt-3" style="display: none;">
                        <img id="thumbnailImg" class="img-fluid rounded" style="max-height: 150px;">
                    </div>
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Preview Image</label>
                    <input type="file" class="form-control @error('preview_image') is-invalid @enderror" 
                           name="preview_image" accept="image/*" id="previewImageInput">
                    <div class="form-text">Full preview image (Max 5MB)</div>
                    <div id="previewImagePreview" class="mt-3" style="display: none;">
                        <img id="previewImageImg" class="img-fluid rounded" style="max-height: 150px;">
                    </div>
                    @error('preview_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Template ZIP File</label>
                    <input type="file" class="form-control @error('template_zip') is-invalid @enderror" 
                           name="template_zip" accept=".zip" required>
                    <div class="form-text">ZIP file containing template files (Max 50MB)</div>
                    @error('template_zip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Version</label>
                    <input type="text" class="form-control @error('version') is-invalid @enderror" 
                           name="version" value="{{ old('version', '1.0.0') }}" 
                           placeholder="1.0.0" required>
                    @error('version')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Author Name</label>
                    <input type="text" class="form-control @error('author') is-invalid @enderror" 
                           name="author" value="{{ old('author') }}" 
                           placeholder="Your name">
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Author Website</label>
                    <input type="url" class="form-control @error('author_url') is-invalid @enderror" 
                           name="author_url" value="{{ old('author_url') }}" 
                           placeholder="https://example.com">
                    @error('author_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Template Configuration (JSON)</label>
                    <textarea class="form-control @error('config') is-invalid @enderror" 
                              name="config" rows="5" 
                              placeholder='{
    "colors": {
        "primary": "#8B4513",
        "secondary": "#D2691E"
    },
    "fonts": {
        "primary": "Poppins",
        "secondary": "Playfair Display"
    },
    "layouts": ["default", "modern"]
}'>{{ old('config') }}</textarea>
                    <div class="form-text">JSON configuration for template options (optional)</div>
                    @error('config')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                               id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActive">
                            Active Template
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" 
                               id="isDefault" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isDefault">
                            Set as Default Template
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.templates.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-upload"></i> Upload Template
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Thumbnail preview
    document.getElementById('thumbnailInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('thumbnailPreview');
                const img = document.getElementById('thumbnailImg');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Preview image preview
    document.getElementById('previewImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('previewImagePreview');
                const img = document.getElementById('previewImageImg');
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Form validation
    document.getElementById('templateForm').addEventListener('submit', function(e) {
        const zipFile = document.querySelector('input[name="template_zip"]').files[0];
        if (!zipFile) {
            e.preventDefault();
            Swal.fire('Error', 'Please select a template ZIP file', 'error');
        }
    });
</script>
@endpush
@endsection