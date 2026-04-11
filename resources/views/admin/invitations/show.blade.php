{{-- resources/views/admin/invitations/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Invitation Details - ' . $invitation->groom_full_name . ' & ' . $invitation->bride_full_name)

@section('content')
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Invitation Details</h3>
        <div class="card-toolbar">
            <div class="btn-group">
                @if($invitation->status == 'draft')
                    <form action="{{ route('admin.invitations.publish', $invitation) }}" method="POST" class="d-inline" id="publishForm">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-success btn-sm me-2" id="publishBtn">
                            <i class="bi bi-globe"></i> Publish Invitation
                        </button>
                    </form>
                @elseif($invitation->status == 'published')
                    <form action="{{ route('admin.invitations.unpublish', $invitation) }}" method="POST" class="d-inline" id="unpublishForm">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-warning btn-sm me-2" id="unpublishBtn">
                            <i class="bi bi-eye-slash"></i> Unpublish
                        </button>
                    </form>
                    <span class="badge badge-success me-2 py-2 px-3">
                        <i class="bi bi-globe"></i> Published
                    </span>
                @endif
                
                <a href="{{ route('admin.invitations.edit', $invitation) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i> Edit Info
                </a>
                <a href="{{ route('admin.invitations.customize-template', $invitation) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-palette"></i> Customize Template
                </a>
                <a href="{{ route('admin.invitations.guests.index', $invitation) }}" class="btn btn-sm btn-info">
                    <i class="bi bi-people"></i> Manage Guests
                </a>
                <button type="button" class="btn btn-sm btn-danger" id="deleteBtn">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Status Banner -->
        @if($invitation->status == 'draft')
            <div class="alert alert-warning mb-6">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
                    <div>
                        <strong>Draft Mode</strong><br>
                        This invitation is currently in <strong>DRAFT</strong> mode and is NOT accessible to the public.
                        Click the "Publish" button to make it publicly available.
                    </div>
                </div>
            </div>
        @elseif($invitation->status == 'published')
            <div class="alert alert-success mb-6">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                    <div>
                        <strong>Published</strong><br>
                        This invitation is <strong>PUBLISHED</strong> and publicly accessible via the invitation link.
                    </div>
                </div>
            </div>
        @endif
        
        <div class="row">
            <div class="col-md-6">
                <h4>Pihak Mempelai Pria</h4>
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Nama Lengkap</th>
                        <td>{{ $invitation->groom_full_name }} ({{ $invitation->groom_nickname }})</td>
                    </tr>
                    <tr>
                        <th>Nama Bapak</th>
                        <td>{{ $invitation->groom_father_name }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $invitation->groom_mother_name }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $invitation->groom_address }}</td>
                    </tr>
                    @if($invitation->groom_photo)
                    <tr>
                        <th>Foto</th>
                        <td>
                            <img src="{{ asset('storage/' . $invitation->groom_photo) }}" class="img-fluid rounded" style="max-height: 100px;">
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <h4>Pihak Mempelai Wanita</h4>
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Nama Lengkap</th>
                        <td>{{ $invitation->bride_full_name }} ({{ $invitation->bride_nickname }})</td>
                    </tr>
                    <tr>
                        <th>Nama Bapak</th>
                        <td>{{ $invitation->bride_father_name }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $invitation->bride_mother_name }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $invitation->bride_address }}</td>
                    </tr>
                    @if($invitation->bride_photo)
                    <tr>
                        <th>Foto</th>
                        <td>
                            <img src="{{ asset('storage/' . $invitation->bride_photo) }}" class="img-fluid rounded" style="max-height: 100px;">
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        @if($invitation->has_akad)
        <div class="separator my-5"></div>
        <h4>Akad Nikah</h4>
        <table class="table table-bordered">
            <tr>
                <th width="20%">Tanggal</th>
                <td>{{ $invitation->akad_date ? $invitation->akad_date->format('l, d F Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>{{ $invitation->akad_time ? $invitation->akad_time->format('H:i') . ' WIB' : '-' }}</td>
            </tr>
            <tr>
                <th>Lokasi</th>
                <td>{{ $invitation->akad_location ?? '-' }}</td>
            </tr>
        </table>
        @endif
        
        @if($invitation->has_reception && $invitation->receptions)
        <div class="separator my-5"></div>
        <h4>Resepsi</h4>
        @foreach($invitation->getReceptionDates() as $index => $reception)
        <table class="table table-bordered mb-3">
            <tr>
                <th width="20%">Nama Resepsi</th>
                <td>{{ $reception['name'] }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ \Carbon\Carbon::parse($reception['date'])->format('l, d F Y') }}</td>
            </tr>
            <tr>
                <th>Lokasi</th>
                <td>{{ $reception['location'] }}</td>
            </tr>
        </table>
        @endforeach
        @endif
        
        <div class="separator my-5"></div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="alert alert-primary text-center">
                    <h5>Total Guests</h5>
                    <h2 class="mb-0">{{ $totalGuests }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-success text-center">
                    <h5>Invitations Sent</h5>
                    <h2 class="mb-0">{{ $sentGuests }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-info text-center">
                    <h5>Confirmed Attendance</h5>
                    <h2 class="mb-0">{{ $confirmedGuests }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-secondary text-center">
                    <h5>Total Wishes</h5>
                    <h2 class="mb-0">{{ $totalWishes }}</h2>
                </div>
            </div>
        </div>
        
        <div class="mt-4 d-flex gap-3">
            <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="btn btn-info">
                <i class="bi bi-eye"></i> Preview Invitation
            </a>
            <button class="btn btn-secondary copy-link" data-link="{{ route('invitation.show', $invitation->slug) }}">
                <i class="bi bi-link"></i> Copy Invitation Link
            </button>
            @if($invitation->status == 'published')
            <button class="btn btn-primary share-wa" data-link="{{ route('invitation.show', $invitation->slug) }}">
                <i class="bi bi-whatsapp"></i> Share via WhatsApp
            </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Copy invitation link
    document.querySelector('.copy-link')?.addEventListener('click', function() {
        const link = this.getAttribute('data-link');
        navigator.clipboard.writeText(link);
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Invitation link copied to clipboard',
            timer: 1500,
            showConfirmButton: false
        });
    });
    
    // Share via WhatsApp
    document.querySelector('.share-wa')?.addEventListener('click', function() {
        const link = this.getAttribute('data-link');
        const title = '{{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}';
        const text = `Undangan Pernikahan ${title}\n\n${link}`;
        window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
    });
    
    // Publish button confirmation
    const publishBtn = document.getElementById('publishBtn');
    if (publishBtn) {
        publishBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Publish Invitation?',
                text: 'This invitation will be publicly accessible via the invitation link.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, publish it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('publishForm').submit();
                }
            });
        });
    }
    
    // Unpublish button confirmation
    const unpublishBtn = document.getElementById('unpublishBtn');
    if (unpublishBtn) {
        unpublishBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Unpublish Invitation?',
                text: 'This invitation will NO LONGER be accessible to the public.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, unpublish it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ffc107'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('unpublishForm').submit();
                }
            });
        });
    }
    
    // Delete button confirmation
    const deleteBtn = document.getElementById('deleteBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Delete Invitation?',
                html: `Are you sure you want to delete <strong>{{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}</strong>'s invitation?<br><small class="text-danger">This action cannot be undone. All guest data will be permanently deleted.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.invitations.destroy", $invitation) }}';
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    }
</script>
@endpush
@endsection