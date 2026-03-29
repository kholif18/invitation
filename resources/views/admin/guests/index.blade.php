@extends('admin.layouts.app')

@section('title', 'Guest List')

@section('content')
<h2>Guest List for "{{ $invitation->title }}"</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Message</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($guests as $guest)
        <tr>
            <td>{{ $guest->name }}</td>
            <td>{{ $guest->email ?? '-' }}</td>
            <td>{{ $guest->status }}</td>
            <td>{{ $guest->message ?? '-' }}</td>
            <td>
                <form action="{{ route('guests.update', $guest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-control d-inline-block w-auto">
                        <option value="pending" @if($guest->status=='pending') selected @endif>Pending</option>
                        <option value="hadir" @if($guest->status=='hadir') selected @endif>Hadir</option>
                        <option value="tidak hadir" @if($guest->status=='tidak hadir') selected @endif>Tidak Hadir</option>
                    </select>
                    <input type="text" name="message" class="form-control d-inline-block w-25" placeholder="Message" value="{{ $guest->message }}">
                    <button class="btn btn-sm btn-primary">Update</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $guests->links() }}
@endsection
