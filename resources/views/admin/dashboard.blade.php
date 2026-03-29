@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Welcome, {{ auth()->user()->name }}</h1>

        <div class="card">
            <div class="card-header">
                <h3>Statistics</h3>
            </div>
            <div class="card-body">
                <p>Total Invitations: {{ $invitationsCount ?? 0 }}</p>
                <p>Total Guests: {{ $guestsCount ?? 0 }}</p>
            </div>
        </div>

        <a href="{{ route('invitations.index') }}" class="btn btn-primary mt-3">Manage Invitations</a>
    </div>
</div>
@endsection
