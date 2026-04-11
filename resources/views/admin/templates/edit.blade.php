{{-- resources/views/admin/templates/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Template - ' . $template->name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('admin.templates.index') }}" class="text-muted text-hover-primary">Templates</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Edit Template</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Template: {{ $template->name }}</h3>
        <div class="card-toolbar">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back to Templates
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.templates.update', $template->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Template Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name', $template->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Category</label>
                    <select class="form-control @error('category') is-invalid @enderror" name="category" required>
                        <option value="classic" {{ old('category', $template->category) == 'classic' ? 'selected' : '' }}>Classic</option>
                        <option value="modern" {{ old('category', $template->category) == 'modern' ? 'selected' : '' }}>Modern</option>
                        <option value="elegant" {{ old('category', $template->category) == 'elegant' ? 'selected' : '' }}>Elegant</option>
                        <option value="jawa" {{ old('category', $template->category) == 'jawa' ? 'selected' : '' }}>Jawa</option>
                        <option value="minimalis" {{ old('category', $template->category) == 'minimalis' ? 'selected' : '' }}>Minimalis</option>
                        <option value="custom" {{ old('category', $template->category) == 'custom' ? 'selected' : '' }}>Custom</option>
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
                              name="description" rows="3">{{ old('description', $template->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Thumbnail Image</label>
                    @if($template->thumbnail)
                        <div class="mb-2">
                            <img src="{{ $template->thumbnail_url }}" class="img-fluid rounded" style="max-height: 100px;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                           name="thumbnail" accept="image/*">
                    <div class="form-text">Leave empty to keep current thumbnail (Max 2MB)</div>
                    @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Version</label>
                    <input type="text" class="form-control @error('version') is-invalid @enderror" 
                           name="version" value="{{ old('version', $template->version) }}" required>
                    @error('version')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Author Name</label>
                    <input type="text" class="form-control @error('author') is-invalid @enderror" 
                           name="author" value="{{ old('author', $template->author) }}">
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Author Website</label>
                    <input type="url" class="form-control @error('author_url') is-invalid @enderror" 
                           name="author_url" value="{{ old('author_url', $template->author_url) }}">
                    @error('author_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Template Configuration (JSON)</label>
                    <textarea class="form-control @error('config') is-invalid @enderror" 
                              name="config" rows="5">{{ old('config', json_encode($template->config, JSON_PRETTY_PRINT)) }}</textarea>
                    <div class="form-text">JSON configuration for template options</div>
                    @error('config')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                               id="isActive" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isActive">
                            Active Template
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" 
                               id="isDefault" {{ old('is_default', $template->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="isDefault">
                            Set as Default Template
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Note:</strong> To update template files (ZIP), please delete this template and upload a new one.
            </div>
            
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.templates.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection