@extends('admin.layouts.app')

@section('title', 'Invitations List')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Invitations</h2>
    <a href="{{ route('invitations.create') }}" class="btn btn-primary">Create New</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Location</th>
            <th>Link</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invitations as $inv)
        <tr>
            <td>{{ $inv->title }}</td>
            <td>{{ $inv->date }}</td>
            <td>{{ $inv->location }}</td>
            <td><a href="{{ url('/invitation/'.$inv->link) }}" target="_blank">View</a></td>
            <td>
                <a href="{{ route('invitations.edit', $inv) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('invitations.destroy', $inv) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $invitations->links() }}
@endsection
