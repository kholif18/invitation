@extends('admin.layouts.app')

@section('title', 'Create Invitation')

@section('content')
<h2>Create New Invitation</h2>

<form action="{{ route('invitations.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Time</label>
        <input type="time" name="time" class="form-control">
    </div>
    <div class="mb-3">
        <label>Location</label>
        <input type="text" name="location" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Theme</label>
        <input type="text" name="theme" class="form-control">
    </div>

    <button class="btn btn-success">Create Invitation</button>
</form>
@endsection
