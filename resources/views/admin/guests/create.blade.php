{{-- resources/views/admin/guests/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add Guest - ' . $invitation->groom_full_name . ' & ' . $invitation->bride_full_name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New Guest</h3>
        <div class="card-toolbar">
            <a href="{{ route('admin.invitations.guests.index', $invitation) }}" class="btn btn-light">
                Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.invitations.guests.store', $invitation) }}" method="POST">
            @csrf
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="required fw-bold mb-2">Guest Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="email@example.com">
                    <div class="form-text">Required if sending via email</div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="fw-bold mb-2">Phone Number</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="+62xxxxxxxxxx">
                    <div class="form-text">Required if sending via WhatsApp</div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Sending Method</label>
                    <select class="form-control @error('sending_method') is-invalid @enderror" name="sending_method">
                        <option value="email" {{ old('sending_method') == 'email' ? 'selected' : '' }}>Email Only</option>
                        <option value="whatsapp" {{ old('sending_method') == 'whatsapp' ? 'selected' : '' }}>WhatsApp Only</option>
                        <option value="both" {{ old('sending_method') == 'both' ? 'selected' : '' }}>Both Email & WhatsApp</option>
                    </select>
                    @error('sending_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="required fw-bold mb-2">Number of Guests</label>
                    <input type="number" class="form-control @error('number_of_guests') is-invalid @enderror" name="number_of_guests" value="{{ old('number_of_guests', 1) }}" min="1" max="10">
                    @error('number_of_guests')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-12">
                    <label class="fw-bold mb-2">Personal Message (Optional)</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="3" placeholder="Personal message for this guest">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('admin.invitations.guests.index', $invitation) }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Guest</button>
            </div>
        </form>
    </div>
</div>
@endsection