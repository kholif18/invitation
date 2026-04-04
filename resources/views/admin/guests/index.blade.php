{{-- resources/views/admin/guests/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Manage Guests - ' . $invitation->groom_full_name . ' & ' . $invitation->bride_full_name)

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.invitations.index') }}" class="text-muted text-hover-primary">All Invitations</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Manage Guests</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Guest List for {{ $invitation->groom_full_name }} & {{ $invitation->bride_full_name }}
        </h3>
        <div class="card-toolbar">
            <a href="{{ route('admin.invitations.guests.create', $invitation) }}" class="btn btn-sm btn-primary me-2">
                <i class="bi bi-plus"></i> Add Guest
            </a>
            <a href="{{ route('admin.invitations.guests.import', $invitation) }}" class="btn btn-sm btn-info me-2">
                <i class="bi bi-upload"></i> Import CSV
            </a>
            <a href="{{ route('admin.invitations.guests.export', $invitation) }}" class="btn btn-sm btn-success">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.invitations.guests.send-bulk', $invitation) }}" method="POST" id="bulkSendForm">
            @csrf
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300 gy-7" id="guestsTable">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th width="50">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Sending Method</th>
                            <th>Invitation Code</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Viewed At</th>
                            <th>Attendance</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guests as $guest)
                        <tr>
                            <td>
                                <input type="checkbox" name="guest_ids[]" value="{{ $guest->id }}" class="guest-checkbox">
                            </td>
                            <td>{{ $guest->name }}</td>
                            <td>{{ $guest->email ?? '-' }}</td>
                            <td>{{ $guest->phone ?? '-' }}</td>
                            <td>
                                @if($guest->sending_method == 'email')
                                    <span class="badge badge-primary">Email</span>
                                @elseif($guest->sending_method == 'whatsapp')
                                    <span class="badge badge-success">WhatsApp</span>
                                @else
                                    <span class="badge badge-info">Both</span>
                                @endif
                            </td>
                            <td>
                                <code>{{ $guest->invitation_code }}</code>
                                <button class="btn btn-sm btn-icon btn-light copy-code" data-code="{{ $guest->invitation_code }}">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </td>
                            <td>
                                @if($guest->is_sent)
                                    <span class="badge badge-success">Sent</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $guest->sent_at ? $guest->sent_at->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $guest->viewed_at ? $guest->viewed_at->format('d M Y H:i') : '-' }}</td>
                            <td>
                                @if($guest->attendance_status == 'confirmed')
                                    <span class="badge badge-success">Confirmed</span>
                                @elseif($guest->attendance_status == 'declined')
                                    <span class="badge badge-danger">Declined</span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if(!$guest->is_sent)
                                    <button type="button" class="btn btn-sm btn-primary send-single" data-id="{{ $guest->id }}" data-name="{{ $guest->name }}">
                                        <i class="bi bi-envelope"></i> Send
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.invitations.guests.edit', [$invitation, $guest]) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-guest" data-id="{{ $guest->id }}" data-name="{{ $guest->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No guests added yet. <a href="{{ route('admin.invitations.guests.create', $invitation) }}">Add your first guest</a></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($guests->count() > 0)
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="bulkSendBtn">
                    <i class="bi bi-send"></i> Send Selected Invitations
                </button>
            </div>
            @endif
        </form>
        
        <div class="mt-4">
            {{ $guests->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.guest-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
    
    // Copy invitation code
    document.querySelectorAll('.copy-code').forEach(button => {
        button.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            navigator.clipboard.writeText(code);
            Swal.fire('Copied!', 'Invitation code copied to clipboard', 'success');
        });
    });
    
    // Send single invitation
    document.querySelectorAll('.send-single').forEach(button => {
        button.addEventListener('click', function() {
            const guestId = this.getAttribute('data-id');
            const guestName = this.getAttribute('data-name');
            
            Swal.fire({
                title: 'Send Invitation?',
                text: `Send invitation to ${guestName}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `{{ route('admin.invitations.guests.send', [$invitation, ':id']) }}`.replace(':id', guestId);
                }
            });
        });
    });
    
    // Delete guest
    document.querySelectorAll('.delete-guest').forEach(button => {
        button.addEventListener('click', function() {
            const guestId = this.getAttribute('data-id');
            const guestName = this.getAttribute('data-name');
            
            Swal.fire({
                title: 'Delete Guest?',
                text: `Are you sure you want to delete ${guestName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('admin.invitations.guests.destroy', [$invitation, ':id']) }}`.replace(':id', guestId);
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
    
    // Bulk send confirmation
    document.getElementById('bulkSendForm').addEventListener('submit', function(e) {
        const selectedCount = document.querySelectorAll('.guest-checkbox:checked').length;
        if (selectedCount === 0) {
            e.preventDefault();
            Swal.fire('Error', 'Please select at least one guest to send invitations', 'error');
        } else {
            e.preventDefault();
            Swal.fire({
                title: 'Send Bulk Invitations?',
                text: `Send invitations to ${selectedCount} guest(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, send them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        }
    });
</script>
@endpush
@endsection