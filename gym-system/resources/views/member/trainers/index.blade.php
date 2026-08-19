@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Book a Personal Trainer</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Trainer List -->
        <div class="col-md-8">
            @foreach($trainers as $trainer)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title">{{ $trainer->user->name }}</h4>
                        <p class="text-muted">{{ $trainer->specialization ?? 'General Fitness' }} | {{ $trainer->bio }}</p>
                        
                        <hr>
                        <h5>Available Time Slots</h5>
                        @if($trainer->availabilities->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($trainer->availabilities as $slot)
                                    <form action="{{ route('member.trainers.book') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="availability_id" value="{{ $slot->id }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            {{ $slot->date->format('M d') }} <br>
                                            <small>{{ $slot->start_time->format('g:i A') }}</small>
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small">No available slots at the moment.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- My Trainer Bookings -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">My Trainer Sessions</div>
                <ul class="list-group list-group-flush">
                    @forelse($myTrainerBookings as $booking)
                        <li class="list-group-item">
                            <strong>{{ $booking->availability->trainer->user->name }}</strong><br>
                            <small class="text-muted">
                                {{ $booking->availability->date->format('M d, Y') }} at 
                                {{ $booking->availability->start_time->format('g:i A') }}
                            </small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">You have no booked sessions.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection