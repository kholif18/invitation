{{-- resources/views/admin/communications/whatsapp/send.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Send WhatsApp Message')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.communications.index') }}" class="text-muted text-hover-primary">
        Communications
    </a>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.communications.whatsapp.index') }}" class="text-muted text-hover-primary">
        WhatsApp Messages
    </a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-200 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-dark">Send Message</li>
@endsection

@section('content')
<div class="row g-6">
    <div class="col-xl-8">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Send WhatsApp Message</h3>
            </div>
            <div class="card-body">
                <div class="mb-6">
                    <label class="required fw-bold mb-2">Recipients</label>
                    <div class="mb-3">
                        <select class="form-select" id="recipientGroup" onchange="loadRecipients()">
                            <option value="">Select recipient group</option>
                            <option value="all">All Guests (245 guests)</option>
                            <option value="confirmed">Confirmed Guests (156 guests)</option>
                            <option value="pending">Pending Guests (89 guests)</option>
                            <option value="declined">Declined Guests (34 guests)</option>
                        </select>
                    </div>
                    <div class="border rounded p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Selected Recipients</span>
                            <span class="badge badge-light-primary" id="selectedCount">0 selected</span>
                        </div>
                        <div id="recipientsList" class="max-h-200px overflow-auto">
                            <p class="text-muted text-center">Select a group to view recipients</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="fw-bold mb-2">Message Template</label>
                    <select class="form-select mb-3" id="messageTemplate" onchange="loadMessageTemplate()">
                        <option value="">Select template</option>
                        <option value="wedding_invitation">Wedding Invitation</option>
                        <option value="rsvp_reminder">RSVP Reminder</option>
                        <option value="location_reminder">Location Reminder</option>
                        <option value="thank_you">Thank You Message</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="required fw-bold mb-2">Message Content</label>
                    <textarea class="form-control" id="messageContent" rows="8" placeholder="Type your message here..."></textarea>
                    <div class="form-text mt-2">
                        <i class="bi bi-info-circle"></i> Available variables: [Guest Name], [Event Name], [Event Date], [Event Time], [Event Location], [RSVP Link]
                    </div>
                </div>
                
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="scheduleMessage">
                    <label class="form-check-label" for="scheduleMessage">Schedule for later</label>
                </div>
                
                <div id="schedulePicker" style="display: none;">
                    <label class="fw-bold mb-2">Schedule Date & Time</label>
                    <input type="datetime-local" class="form-control" id="scheduleDate">
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">Preview</h3>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-light" id="previewContent">
                    <div class="text-center">
                        <i class="bi bi-chat-dots fs-2x text-muted"></i>
                        <p class="text-muted mt-2">Message preview will appear here</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Available Variables</h3>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <button class="list-group-item list-group-item-action" onclick="insertVariable('[Guest Name]')">
                        <i class="bi bi-person me-2"></i> [Guest Name] - Guest's full name
                    </button>
                    <button class="list-group-item list-group-item-action" onclick="insertVariable('[Event Name]')">
                        <i class="bi bi-calendar-event me-2"></i> [Event Name] - Wedding/event name
                    </button>
                    <button class="list-group-item list-group-item-action" onclick="insertVariable('[Event Date]')">
                        <i class="bi bi-calendar-date me-2"></i> [Event Date] - Date of the event
                    </button>
                    <button class="list-group-item list-group-item-action" onclick="insertVariable('[Event Time]')">
                        <i class="bi bi-clock me-2"></i> [Event Time] - Time of the event
                    </button>
                    <button class="list-group-item list-group-item-action" onclick="insertVariable('[Event Location]')">
                        <i class="bi bi-geo-alt me-2"></i> [Event Location] - Venue address
                    </button>
                    <button class="list-group-item list-group-item-action" onclick="insertVariable('[RSVP Link]')">
                        <i class="bi bi-link me-2"></i> [RSVP Link] - Personalized RSVP link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-3 mt-6">
    <a href="{{ route('admin.communications.whatsapp.index') }}" class="btn btn-light">Cancel</a>
    <button type="button" class="btn btn-success" onclick="sendMessage()">
        <i class="bi bi-whatsapp"></i> Send Message
    </button>
</div>

@push('styles')
<style>
.max-h-200px {
    max-height: 200px;
}
.overflow-auto {
    overflow-y: auto;
}
</style>
@endpush

@push('scripts')
<script>
    let recipients = [];
    
    function loadRecipients() {
        const group = document.getElementById('recipientGroup').value;
        const recipientsList = document.getElementById('recipientsList');
        
        // Dummy recipients data
        const dummyRecipients = {
            all: [
                { name: 'John Doe', phone: '+628123456789' },
                { name: 'Jane Smith', phone: '+628987654321' },
                { name: 'Robert Johnson', phone: '+628555555555' },
                { name: 'Maria Garcia', phone: '+628777777777' },
                { name: 'David Chen', phone: '+628999999999' }
            ],
            confirmed: [
                { name: 'John Doe', phone: '+628123456789' },
                { name: 'Jane Smith', phone: '+628987654321' },
                { name: 'David Chen', phone: '+628999999999' }
            ],
            pending: [
                { name: 'Robert Johnson', phone: '+628555555555' }
            ],
            declined: [
                { name: 'Maria Garcia', phone: '+628777777777' }
            ]
        };
        
        if(group && dummyRecipients[group]) {
            recipients = dummyRecipients[group];
            let html = '';
            recipients.forEach(recipient => {
                html += `
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-bold">${recipient.name}</div>
                            <div class="text-muted fs-7">${recipient.phone}</div>
                        </div>
                        <input type="checkbox" class="form-check-input recipient-checkbox" value="${recipient.phone}" checked>
                    </div>
                `;
            });
            recipientsList.innerHTML = html;
            document.getElementById('selectedCount').textContent = `${recipients.length} selected`;
            
            // Add checkbox events
            document.querySelectorAll('.recipient-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSelectedCount);
            });
        } else {
            recipientsList.innerHTML = '<p class="text-muted text-center">Select a group to view recipients</p>';
            document.getElementById('selectedCount').textContent = '0 selected';
        }
    }
    
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.recipient-checkbox:checked');
        document.getElementById('selectedCount').textContent = `${checkboxes.length} selected`;
    }
    
    function loadMessageTemplate() {
        const template = document.getElementById('messageTemplate').value;
        const content = document.getElementById('messageContent');
        
        const templates = {
            'wedding_invitation': `Hi [Guest Name]! 🎉

You're invited to celebrate the wedding of [Groom Name] & [Bride Name]!

📅 Date: [Event Date]
⏰ Time: [Event Time]
📍 Venue: [Event Location]

Please RSVP here: [RSVP Link]

We can't wait to celebrate with you! 💕`,
            'rsvp_reminder': `Hi [Guest Name]! 👋

We haven't received your RSVP for our wedding yet. Could you please confirm your attendance?

Click here: [RSVP Link]

Thank you! 🙏`,
            'location_reminder': `Hi [Guest Name]! 📍

Don't forget! Our wedding is tomorrow at [Event Time] at [Event Location].

We can't wait to see you there! 🎉`,
            'thank_you': `Hi [Guest Name]! 💕

Thank you so much for celebrating our special day with us! Your presence made our wedding truly memorable.

We appreciate your love and support! 🙏`
        };
        
        if(templates[template]) {
            content.value = templates[template];
            updatePreview();
        }
    }
    
    function updatePreview() {
        const content = document.getElementById('messageContent').value;
        const preview = document.getElementById('previewContent');
        
        let previewHtml = content.replace(/\[Guest Name\]/g, '<strong>John Doe</strong>')
            .replace(/\[Event Name\]/g, '<strong>John & Sarah\'s Wedding</strong>')
            .replace(/\[Event Date\]/g, '<strong>December 25, 2024</strong>')
            .replace(/\[Event Time\]/g, '<strong>6:00 PM</strong>')
            .replace(/\[Event Location\]/g, '<strong>Grand Ballroom, Hotel Indonesia</strong>')
            .replace(/\[RSVP Link\]/g, '<a href="#">RSVP Here</a>')
            .replace(/\n/g, '<br>');
        
        preview.innerHTML = `
            <div class="bg-white rounded p-3 shadow-sm">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-whatsapp text-success fs-3 me-2"></i>
                    <span class="fw-bold">Preview</span>
                </div>
                <div class="text-muted fs-7 mb-2">To: John Doe</div>
                <div>${previewHtml}</div>
            </div>
        `;
    }
    
    function insertVariable(variable) {
        const content = document.getElementById('messageContent');
        const start = content.selectionStart;
        const end = content.selectionEnd;
        const text = content.value;
        const before = text.substring(0, start);
        const after = text.substring(end, text.length);
        content.value = before + variable + after;
        content.focus();
        content.setSelectionRange(start + variable.length, start + variable.length);
        updatePreview();
    }
    
    document.getElementById('messageContent').addEventListener('input', updatePreview);
    document.getElementById('scheduleMessage').addEventListener('change', function() {
        document.getElementById('schedulePicker').style.display = this.checked ? 'block' : 'none';
    });
    
    function sendMessage() {
        const selectedRecipients = document.querySelectorAll('.recipient-checkbox:checked');
        
        if(selectedRecipients.length === 0) {
            Swal.fire('Error', 'Please select at least one recipient', 'error');
            return;
        }
        
        const message = document.getElementById('messageContent').value;
        if(!message.trim()) {
            Swal.fire('Error', 'Please enter a message', 'error');
            return;
        }
        
        const schedule = document.getElementById('scheduleMessage').checked;
        const scheduleDate = document.getElementById('scheduleDate').value;
        
        if(schedule && !scheduleDate) {
            Swal.fire('Error', 'Please select a schedule date and time', 'error');
            return;
        }
        
        const recipientsList = Array.from(selectedRecipients).map(cb => cb.value);
        
        Swal.fire({
            title: schedule ? 'Schedule Message?' : 'Send Message?',
            text: `This will ${schedule ? 'schedule' : 'send'} the message to ${selectedRecipients.length} recipient(s).`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: schedule ? 'Yes, schedule!' : 'Yes, send!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.communications.whatsapp.send") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        recipients: recipientsList,
                        message: message,
                        schedule_date: schedule ? scheduleDate : null
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire('Success', response.message, 'success')
                                .then(() => {
                                    window.location.href = '{{ route("admin.communications.whatsapp.index") }}';
                                });
                        }
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection