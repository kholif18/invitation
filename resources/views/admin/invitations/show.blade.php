{{-- resources/views/admin/invitations/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Invitation Details - ' . $invitation->groom_full_name . ' & ' . $invitation->bride_full_name)

@section('content')
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Invitation Details</h3>
        <div class="card-toolbar">
            <div class="btn-group">
                <a href="{{ route('admin.invitations.edit', $invitation) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit Info
                </a>
                <a href="{{ route('admin.invitations.customize-template', $invitation) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-palette"></i> Customize Template
                </a>
                <a href="{{ route('admin.invitations.guests.index', $invitation) }}" class="btn btn-sm btn-info">
                    <i class="bi bi-people"></i> Manage Guests
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Rest of the show content -->
        <div class="row">
            <div class="col-md-6">
                <h4>Pihak Mempelai Pria</h4>
                <p><strong>Nama:</strong> {{ $invitation->groom_full_name }} ({{ $invitation->groom_nickname }})</p>
                <p><strong>Orang Tua:</strong> {{ $invitation->groom_father_name }} & {{ $invitation->groom_mother_name }}</p>
                <p><strong>Alamat:</strong> {{ $invitation->groom_address }}</p>
            </div>
            <div class="col-md-6">
                <h4>Pihak Mempelai Wanita</h4>
                <p><strong>Nama:</strong> {{ $invitation->bride_full_name }} ({{ $invitation->bride_nickname }})</p>
                <p><strong>Orang Tua:</strong> {{ $invitation->bride_father_name }} & {{ $invitation->bride_mother_name }}</p>
                <p><strong>Alamat:</strong> {{ $invitation->bride_address }}</p>
            </div>
        </div>
        
        <div class="separator my-5"></div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-primary">
                    <h5>Total Guests</h5>
                    <h2 class="mb-0">{{ $totalGuests }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success">
                    <h5>Invitations Sent</h5>
                    <h2 class="mb-0">{{ $sentGuests }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info">
                    <h5>Confirmed Attendance</h5>
                    <h2 class="mb-0">{{ $confirmedGuests }}</h2>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="btn btn-info">
                <i class="bi bi-eye"></i> Preview Invitation
            </a>
            <button class="btn btn-secondary copy-link" data-link="{{ route('invitation.show', $invitation->slug) }}">
                <i class="bi bi-link"></i> Copy Invitation Link
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('.copy-link')?.addEventListener('click', function() {
        const link = this.getAttribute('data-link');
        navigator.clipboard.writeText(link);
        Swal.fire('Copied!', 'Invitation link copied to clipboard', 'success');
    });
</script>
@endpush
@endsection