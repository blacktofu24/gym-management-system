@extends('layouts.app')

@section('content')
<div class="container">
    
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Available Slots -->
        <div class="col-md-8">
            <h3 class="mb-3">Available Time Slots</h3>
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($timeSlots as $slot)
                                @php
                                    $isFull = $slot->bookings_count >= $slot->max_capacity;
                                @endphp
                                <tr>
                                    <td><strong>{{ $slot->date->format('M d, Y') }}</strong></td>
                                    <td>{{ $slot->start_time->format('g:i A') }} - {{ $slot->end_time->format('g:i A') }}</td>
                                    <td>
                                        <span class="badge {{ $isFull ? 'bg-danger' : 'bg-info' }} fs-6">
                                            {{ $slot->bookings_count }} / {{ $slot->max_capacity }}
                                        </span>
                                        @if($isFull)
                                            <div class="text-danger small fw-bold mt-1">FULL</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($isFull)
                                            @if(in_array($slot->id, $myWaitlists))
                                                <button class="btn btn-secondary btn-sm" disabled>On Waitlist</button>
                                            @else
                                                <form action="{{ route('member.waitlist.join') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="time_slot_id" value="{{ $slot->id }}">
                                                    <button type="submit" class="btn btn-warning btn-sm" {{ !$activeMembership ? 'disabled' : '' }}>
                                                        Join Waitlist
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <form action="{{ route('member.bookings.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="time_slot_id" value="{{ $slot->id }}">
                                                <button type="submit" class="btn btn-primary btn-sm" {{ !$activeMembership ? 'disabled' : '' }}>
                                                    Book Slot
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted py-4">No available time slots found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- My Upcoming Bookings -->
        <div class="col-md-4">
            <h3 class="mb-3">My Bookings</h3>
            @forelse($myBookings as $booking)
                <div class="card shadow-sm mb-3 border-start border-4 border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">{{ $booking->timeSlot->date->format('M d, Y') }}</h5>
                        <h6 class="card-subtitle mb-3 text-muted">
                            {{ $booking->timeSlot->start_time->format('g:i A') }} - {{ $booking->timeSlot->end_time->format('g:i A') }}
                        </h6>
                        <span class="badge bg-success mb-3">Confirmed</span>
                        
                        @if($booking->qr_token)
                            <div class="p-3 bg-light rounded mb-3 d-inline-block">
                                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(route('admin.checkin.scan', $booking->qr_token)) !!}
                            </div>
                            <p class="small text-muted mb-1">Present this QR code at the front desk</p>
                            
                            <div class="alert alert-warning p-1 text-break" style="font-size: 10px;">
                                {{ route('admin.checkin.scan', $booking->qr_token) }}
                            </div>
                        @endif
                        
                        <!-- NEW SWEETALERT CANCEL FORM -->
                        <!-- Notice the class="cancel-form" and the removed onsubmit="" -->
                        <form action="{{ route('member.bookings.cancel', $booking->id) }}" method="POST" class="cancel-form">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Cancel Booking</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-light border">
                    You have no upcoming bookings.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection