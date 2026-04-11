{{-- resources/views/admin/templates/select.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Select Wedding Invitation Template')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Select Template</li>
@endsection

@section('content')
<div class="mb-6">
    <h2>Choose Your Template</h2>
    <p class="text-muted">Select a template to start creating your wedding invitation</p>
</div>

<div class="row g-6">
    @forelse($templates as $template)
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card h-100 template-card @if($template->is_default) border-primary @endif">
            <div class="position-relative">
                <img src="{{ $template->thumbnail_url }}" 
                    class="card-img-top" 
                    alt="{{ $template->name }}" 
                    style="height: 200px; object-fit: cover;"
                    onerror="this.onerror=null; this.src='https://placehold.co/600x400/F0F0F0/999999?text={{ urlencode($template->name) }}';">
                <div class="position-absolute top-0 end-0 m-2">
                    @if($template->is_default)
                        <span class="badge badge-primary">Default</span>
                    @endif
                    @if(!$template->is_active)
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $template->name }}</h5>
                <p class="card-text text-muted small">{{ Str::limit($template->description, 100) }}</p>
                
                <div class="mb-2">
                    <span class="badge badge-light-info">{{ ucfirst($template->category) }}</span>
                    <span class="badge badge-light-secondary">v{{ $template->version }}</span>
                </div>
                
                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('admin.templates.preview', $template->slug) }}" class="btn btn-sm btn-light flex-fill" target="_blank">
                        <i class="bi bi-eye"></i> Preview
                    </a>
                    @if($template->is_active)
                    <a href="{{ route('admin.invitations.create', ['template' => $template->slug]) }}" class="btn btn-sm btn-primary flex-fill">
                        <i class="bi bi-plus-circle"></i> Select
                    </a>
                    @else
                    <button class="btn btn-sm btn-secondary flex-fill" disabled>
                        <i class="bi bi-lock"></i> Inactive
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-10">
            <i class="bi bi-grid-3x3-gap-fill fs-1 text-muted"></i>
            <h3 class="mt-3">No Templates Available</h3>
            <p class="text-muted">Please upload a template first before creating an invitation.</p>
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="bi bi-upload"></i> Upload Template
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($templates->count() > 0)
<div class="mt-6 text-center">
    <a href="{{ route('admin.templates.index') }}" class="btn btn-link">
        <i class="bi bi-grid-3x3-gap-fill"></i> Browse All Templates
    </a>
</div>
@endif

@push('styles')
<style>
    .template-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    
    .template-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .card-img-top {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .btn-group {
        width: 100%;
    }
</style>
@endpush
@endsection