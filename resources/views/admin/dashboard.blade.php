@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0">Here's what's happening with your invitations today.</p>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-envelope fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-muted mb-2">Total Invitations</h5>
                            <h2 class="mb-0">{{ $invitationsCount ?? 0 }}</h2>
                        </div>
                        <div class="avatar-sm bg-primary-light rounded-circle p-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-success">+{{ $newInvitations ?? 0 }} this week</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-muted mb-2">Total Guests</h5>
                            <h2 class="mb-0">{{ $guestsCount ?? 0 }}</h2>
                        </div>
                        <div class="avatar-sm bg-success-light rounded-circle p-3">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-info">{{ $confirmedGuests ?? 0 }} confirmed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-muted mb-2">Attendance Rate</h5>
                            <h2 class="mb-0">{{ $attendanceRate ?? 0 }}%</h2>
                        </div>
                        <div class="avatar-sm bg-warning-light rounded-circle p-3">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: {{ $attendanceRate ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-muted mb-2">Pending Invitations</h5>
                            <h2 class="mb-0">{{ $pendingInvitations ?? 0 }}</h2>
                        </div>
                        <div class="avatar-sm bg-danger-light rounded-circle p-3">
                            <i class="fas fa-clock fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="text-danger">View pending →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title align-items-start flex-column">Invitation Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="invitationChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title align-items-start flex-column">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.invitations.templates') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i> Create New Invitation
                        </a>
                        <a href="#" class="btn btn-info btn-lg">
                            <i class="fas fa-list me-2"></i> Manage Invitations
                        </a>
                        <a href="#" class="btn btn-success btn-lg">
                            <i class="fas fa-download me-2"></i> Export Guest List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invitations Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title align-items-start flex-column">Recent Invitations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Guests</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse($recentInvitations ?? [] as $invitation) --}}
                                <tr>
                                    {{-- <td>{{ $loop->iteration }}</td>
                                    <td>{{ $invitation->event_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invitation->event_date)->format('d M Y') }}</td>
                                    <td>{{ $invitation->guests_count ?? 0 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $invitation->status === 'sent' ? 'success' : 'warning' }}">
                                            {{ ucfirst($invitation->status) }}
                                        </span>
                                    </td> --}}
                                    <td>Iteration</td>
                                    <td>Event Name</td>
                                    <td>Date</td>
                                    <td>Guests</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                {{-- @empty --}}
                                <tr>
                                    <td colspan="6" class="text-center">No invitations found</td>
                                </tr>
                                {{-- @endforelse --}}
                            </tbody>
                        </table>
                    </div>
                    {{-- @if(isset($recentInvitations) && $recentInvitations->count() > 0) --}}
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.invitations.index') }}" class="btn btn-link">View All Invitations →</a>
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart initialization
    const ctx = document.getElementById('invitationChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Invitations Sent',
                data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
.card-hover {
    transition: transform 0.2s ease-in-out;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.avatar-sm {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-primary-light {
    background-color: rgba(75, 107, 251, 0.1);
}
.bg-success-light {
    background-color: rgba(40, 199, 111, 0.1);
}
.bg-warning-light {
    background-color: rgba(255, 159, 67, 0.1);
}
.bg-danger-light {
    background-color: rgba(255, 85, 85, 0.1);
}
.opacity-50 {
    opacity: 0.5;
}
</style>
@endpush
@endsection