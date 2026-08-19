@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Trainer Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Add Availability -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">Add Availability</div>
                <div class="card-body">
                    <form action="{{ route('trainer.availability.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Slot</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Schedule & Bookings -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">My Upcoming Client Sessions</div>
                <ul class="list-group list-group-flush">
                    @forelse($upcomingSessions as $session)
                        <li class="list-group-item">
                            <strong>{{ $session->user->name }}</strong> 
                            <span class="text-muted">
                                on {{ $session->availability->date->format('M d') }} 
                                from {{ $session->availability->start_time->format('g:i A') }} - {{ $session->availability->end_time->format('g:i A') }}
                            </span>
                            <span class="badge bg-success float-end">Scheduled</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No upcoming client sessions.</li>
                    @endforelse
                </ul>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">My Available Slots (Unbooked)</div>
                <ul class="list-group list-group-flush">
                    @foreach($availabilities->where('is_booked', false) as $slot)
                        <li class="list-group-item">
                            {{ $slot->date->format('M d, Y') }} | 
                            {{ $slot->start_time->format('g:i A') }} - {{ $slot->end_time->format('g:i A') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection