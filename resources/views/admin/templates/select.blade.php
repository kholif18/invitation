{{-- resources/views/admin/templates/select.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Select Wedding Invitation Template')

@section('content')
<div class="mb-6">
    <h2>Choose Your Template</h2>
    <p class="text-muted">Select a template to start creating your wedding invitation</p>
</div>

<div class="row g-6">
    @foreach($templates as $template)
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card h-100 template-card @if($template->is_default) border-primary @endif">
            <div class="card-header p-0">
                <div class="position-relative">
                    <img src="{{ $template->thumbnail_url }}" class="card-img-top" alt="{{ $template->name }}" style="height: 250px; object-fit: cover;">
                    @if($template->is_default)
                        <span class="badge badge-primary position-absolute top-0 end-0 m-2">Default</span>
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
                
                <div class="mt-3">
                    <a href="{{ route('admin.templates.preview', $template) }}" class="btn btn-sm btn-light me-2" target="_blank">
                        <i class="bi bi-eye"></i> Preview
                    </a>
                    <a href="{{ route('admin.invitations.create', ['template' => $template->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Select
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6 text-center">
    <a href="{{ route('admin.templates.index') }}" class="btn btn-link">
        <i class="bi bi-grid-3x3-gap-fill"></i> Browse All Templates
    </a>
</div>
@endsection

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
</style>
@endpush