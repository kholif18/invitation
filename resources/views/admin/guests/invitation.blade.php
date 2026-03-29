@extends('admin.layouts.app')

@section('title', $invitation->title)

@section('content')
<h2>{{ $invitation->title }}</h2>
<p>{{ $invitation->description }}</p>
<p>Date: {{ $invitation->date }} Time: {{ $invitation->time ?? '-' }}</p>
<p>Location: {{ $invitation->location }}</p>

<h4>RSVP</h4>
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('guest.rsvp', $invitation->link) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="hadir">Hadir</option>
            <option value="tidak hadir">Tidak Hadir</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Message (optional)</label>
        <textarea name="message" class="form-control"></textarea>
    </div>
    <button class="btn btn-success">Submit RSVP</button>
</form>
@endsection
