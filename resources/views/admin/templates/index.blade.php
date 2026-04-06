{{-- resources/views/admin/templates/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Templates Management')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Templates</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Wedding Invitation Templates</h3>
        <div class="card-toolbar">
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="bi bi-upload"></i> Upload New Template
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($templates->count() > 0)
        <div class="row g-6">
            @foreach($templates as $template)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="position-relative">
                        <img src="{{ $template->thumbnail_url }}" class="card-img-top" alt="{{ $template->name }}" style="height: 200px; object-fit: cover;">
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
                        <p class="card-text text-muted small">{{ Str::limit($template->description, 80) }}</p>
                        
                        <div class="mb-2">
                            <span class="badge badge-light-info">{{ ucfirst($template->category) }}</span>
                            <span class="badge badge-light-secondary">v{{ $template->version }}</span>
                        </div>
                        
                        <div class="mt-3">
                            <div class="btn-group w-100">
                                <a href="{{ route('admin.templates.preview', $template) }}" class="btn btn-sm btn-light" target="_blank">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-light">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(!$template->is_default)
                                <button type="button" class="btn btn-sm btn-light set-default" data-url="{{ route('admin.templates.set-default', $template) }}">
                                    <i class="bi bi-star"></i>
                                </button>
                                @endif
                                @if($template->invitations()->count() === 0)
                                <button type="button" class="btn btn-sm btn-light-danger delete-template" data-url="{{ route('admin.templates.destroy', $template) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $templates->links() }}
        </div>
        @else
        <div class="text-center py-10">
            <i class="bi bi-grid-3x3-gap-fill fs-1 text-muted"></i>
            <h3 class="mt-3">No Templates Yet</h3>
            <p class="text-muted">Upload your first template to get started.</p>
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="bi bi-upload"></i> Upload Template
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Set as default template
    document.querySelectorAll('.set-default').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const templateName = this.closest('.card').querySelector('.card-title').innerText;
            
            Swal.fire({
                title: 'Set as Default?',
                text: `Make "${templateName}" the default template?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, set as default'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit via POST
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
    
    // Delete template
    document.querySelectorAll('.delete-template').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const templateName = this.closest('.card').querySelector('.card-title').innerText;
            
            Swal.fire({
                title: 'Delete Template?',
                text: `Are you sure you want to delete "${templateName}"? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection