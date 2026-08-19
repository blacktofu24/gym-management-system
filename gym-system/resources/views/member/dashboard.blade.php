@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Member Dashboard</h2>

    <!-- Top Action Buttons -->
    <div class="d-grid gap-2 d-md-block mb-4">
        <a href="{{ route('member.bookings.index') }}" class="btn btn-primary btn-lg me-md-2 mb-2 mb-md-0">
            <i class="fas fa-calendar-plus"></i> Book a Gym Session
        </a>
        <a href="{{ route('member.trainers.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-user-ninja"></i> Book a Trainer
        </a>
    </div>

    <!-- Main Statistics Cards Row -->
    <div class="row mb-4">
        
        <!-- Membership Status Card -->
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Membership Status</h5>
                </div>
                <div class="card-body">
                    @if($activeMembership)
                        <h3 class="text-success">{{ $activeMembership->plan->name }} Plan</h3>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                        <p class="mb-0"><strong>Valid Until:</strong> {{ $activeMembership->end_date->format('M d, Y') }}</p>
                    @else
                        <h3 class="text-danger">No Active Membership</h3>
                        <p class="text-muted">You currently do not have an active membership. Please purchase a plan to start booking.</p>
                        <a href="{{ route('member.plans.index') }}" class="btn btn-primary">View Plans</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gym Occupancy Card -->
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Live Gym Capacity</h5>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h1 class="display-3 fw-bold mb-0">
                        {{ $currentOccupancy }}<span class="text-muted fs-3">/{{ $maxCapacity }}</span>
                    </h1>
                    <p class="lead mb-4">Current Occupancy</p>
                    
                    @php
                        // Prevent division by zero just in case
                        $percentage = $maxCapacity > 0 ? ($currentOccupancy / $maxCapacity) * 100 : 0;
                        $colorClass = $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success');
                    @endphp
                    
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar {{ $colorClass }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $currentOccupancy }}" aria-valuemin="0" aria-valuemax="{{ $maxCapacity }}"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Notifications Row (From Phase 7) -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-bell text-warning"></i> Recent Notifications</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse(Auth::user()->unreadNotifications as $notification)
                            <li class="list-group-item p-3 bg-light border-start border-4 border-warning">
                                <strong>{{ $notification->data['title'] }}</strong><br>
                                {{ $notification->data['message'] }} <br>
                                <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-success mt-2">Claim Spot Now</a>
                            </li>
                        @empty
                            <li class="list-group-item p-4 text-center text-muted">
                                You have no new notifications.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection