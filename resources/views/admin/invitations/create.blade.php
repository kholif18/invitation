{{-- resources/views/admin/invitations/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Create Invitation')

@section('breadcrumb')
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
        Dashboard
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('invitations.index') }}" class="text-muted text-hover-primary">
        All Invitations
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Create Invitation</li>
@endsection

@section('content')
<form id="invitationForm">
    <div class="row g-6">
        <!-- Main Form Column -->
        <div class="col-xl-8">
            <!-- Basic Information -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Basic Information</h3>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary">Required fields*</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Event Name</label>
                            <input type="text" class="form-control" placeholder="e.g., John & Sarah Wedding" value="Wedding Invitation Sample">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Event Type</label>
                            <select class="form-select">
                                <option value="wedding" selected>Wedding</option>
                                <option value="birthday">Birthday Party</option>
                                <option value="corporate">Corporate Event</option>
                                <option value="graduation">Graduation Ceremony</option>
                                <option value="anniversary">Anniversary</option>
                                <option value="baby_shower">Baby Shower</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="required fw-bold mb-2">Event Date & Time</label>
                            <input type="datetime-local" class="form-control" value="{{ \Carbon\Carbon::now()->addDays(30)->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="required fw-bold mb-2">Event Location</label>
                            <input type="text" class="form-control" placeholder="Venue name and address" value="Grand Ballroom, Hotel Indonesia, Jakarta">
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Description</label>
                            <textarea class="form-control" rows="4" placeholder="Event description, dress code, special instructions...">Join us in celebrating this special moment filled with joy and happiness. 
Dress code: Formal
Please confirm your attendance by RSVP</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Invitation Content -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Invitation Content</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">Message to Guests</label>
                            <textarea class="form-control" rows="6" placeholder="Personal message to guests...">Dear Friends and Family,

We are thrilled to invite you to celebrate our special day with us. Your presence would mean the world to us as we embark on this beautiful journey together.

We look forward to sharing this memorable moment with you.

Warm regards,
John & Sarah</textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-6">
                        <div class="col-md-12">
                            <label class="fw-bold mb-2">RSVP Instructions</label>
                            <textarea class="form-control" rows="3" placeholder="RSVP instructions...">Please RSVP by {{ \Carbon\Carbon::now()->addDays(20)->format('d M Y') }}
You can confirm your attendance through the link provided in the invitation email.</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-xl-4">
            <!-- Template Selection -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Invitation Template</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="template" id="template1" checked>
                            <label class="form-check-label" for="template1">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Elegant Classic</span>
                                    <span class="text-muted fs-7">Traditional and formal design</span>
                                </div>
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="template" id="template2">
                            <label class="form-check-label" for="template2">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Modern Minimalist</span>
                                    <span class="text-muted fs-7">Clean and contemporary style</span>
                                </div>
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="template" id="template3">
                            <label class="form-check-label" for="template3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Floral Romance</span>
                                    <span class="text-muted fs-7">Beautiful floral patterns</span>
                                </div>
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="template" id="template4">
                            <label class="form-check-label" for="template4">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Premium Gold</span>
                                    <span class="text-muted fs-7">Luxury golden accents</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="separator my-5"></div>
                    
                    <a href="{{ route('invitations.templates') }}" class="btn btn-light-primary w-100">
                        <i class="ki-duotone ki-palette"></i>
                        Browse More Templates
                    </a>
                </div>
            </div>
            
            <!-- Guest Management -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Guest Management</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="fw-bold mb-2">Add Guests</label>
                        <div class="d-flex gap-2 mb-3">
                            <input type="email" class="form-control" placeholder="guest@email.com" id="guestEmail">
                            <button type="button" class="btn btn-primary" id="addGuestBtn">
                                <i class="ki-duotone ki-plus"></i>
                                Add
                            </button>
                        </div>
                        <div class="border rounded p-3 min-h-150px">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Guest List</span>
                                <span class="badge badge-light-primary" id="guestCount">0 guests</span>
                            </div>
                            <div id="guestList">
                                <div class="text-center text-muted py-4">
                                    <i class="ki-duotone ki-users fs-2x mb-2"></i>
                                    <p>No guests added yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="separator my-5"></div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light-primary w-50">
                            <i class="ki-duotone ki-file-up"></i>
                            Import CSV
                        </button>
                        <button type="button" class="btn btn-light-primary w-50">
                            <i class="ki-duotone ki-download"></i>
                            Export Template
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Settings</h3>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch form-check-custom form-check-solid mb-4">
                        <input class="form-check-input" type="checkbox" id="sendEmail" checked>
                        <label class="form-check-label" for="sendEmail">
                            Send invitation via email
                        </label>
                    </div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid mb-4">
                        <input class="form-check-input" type="checkbox" id="sendSMS">
                        <label class="form-check-label" for="sendSMS">
                            Send SMS notification
                        </label>
                    </div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" id="allowPlusOne">
                        <label class="form-check-label" for="allowPlusOne">
                            Allow guests to bring plus one
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="d-flex justify-content-end gap-3 mt-6">
        <a href="{{ route('invitations.index') }}" class="btn btn-light">
            Cancel
        </a>
        <button type="button" class="btn btn-secondary" id="saveDraftBtn">
            <i class="ki-duotone ki-save"></i>
            Save as Draft
        </button>
        <button type="button" class="btn btn-primary" id="sendInvitationBtn">
            <i class="ki-duotone ki-send"></i>
            Send Invitation
        </button>
    </div>
</form>

@push('scripts')
<script>
    let guests = [];
    
    // Add Guest
    document.getElementById('addGuestBtn').addEventListener('click', function() {
        let email = document.getElementById('guestEmail').value;
        if(email && email.includes('@')) {
            guests.push(email);
            updateGuestList();
            document.getElementById('guestEmail').value = '';
        } else {
            Swal.fire('Error', 'Please enter a valid email address', 'error');
        }
    });
    
    function updateGuestList() {
        let guestListDiv = document.getElementById('guestList');
        let guestCount = document.getElementById('guestCount');
        
        if(guests.length === 0) {
            guestListDiv.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="ki-duotone ki-users fs-2x mb-2"></i>
                    <p>No guests added yet</p>
                </div>
            `;
            guestCount.textContent = '0 guests';
            return;
        }
        
        let html = '<div class="list-group list-group-flush">';
        guests.forEach((guest, index) => {
            html += `
                <div class="d-flex justify-content-between align-items-center py-2">
                    <div>
                        <i class="ki-duotone ki-profile-circle fs-3 text-primary me-2"></i>
                        <span>${guest}</span>
                    </div>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="removeGuest(${index})">
                        <i class="ki-duotone ki-cross fs-2"></i>
                    </button>
                </div>
            `;
        });
        html += '</div>';
        guestListDiv.innerHTML = html;
        guestCount.textContent = guests.length + ' guest' + (guests.length !== 1 ? 's' : '');
    }
    
    window.removeGuest = function(index) {
        guests.splice(index, 1);
        updateGuestList();
    };
    
    // Save Draft
    document.getElementById('saveDraftBtn').addEventListener('click', function() {
        Swal.fire({
            icon: 'success',
            title: 'Draft Saved!',
            text: 'Your invitation has been saved as draft.',
            timer: 2000
        });
    });
    
    // Send Invitation
    document.getElementById('sendInvitationBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Send Invitation?',
            text: `This will send invitation to ${guests.length} guest(s)`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Invitation Sent!',
                    text: 'Your invitation has been sent successfully.',
                    timer: 2000
                }).then(() => {
                    window.location.href = "{{ route('invitations.index') }}";
                });
            }
        });
    });
</script>
@endpush
@endsection