@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Choose Your Membership</h1>
        <p class="lead text-muted">Select the plan that fits your fitness goals.</p>
    </div>

    <div class="row justify-content-center">
        @forelse($plans as $plan)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-primary text-center">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="my-0 fw-normal">{{ $plan->name }}</h4>
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <h1 class="card-title pricing-card-title mb-4">
                            ₱{{ number_format($plan->price, 2) }}<small class="text-muted fw-light fs-5">/{{ $plan->duration_in_days }} days</small>
                        </h1>
                        
                        <p class="mb-4 text-muted">{{ $plan->description }}</p>
                        
                        <ul class="list-unstyled mt-3 mb-4 text-start">
                            <li><i class="fas fa-check text-success me-2"></i> Full Gym Access</li>
                            <li><i class="fas fa-check text-success me-2"></i> QR Code Check-ins</li>
                            <li><i class="fas fa-check text-success me-2"></i> App Class Booking</li>
                            <li><i class="fas fa-check text-success me-2"></i> Book Personal Trainers</li>
                        </ul>
                        
                        <form action="{{ route('member.plans.purchase') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button type="submit" class="btn btn-lg w-100 btn-primary">Simulate Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-warning">
                    No membership plans have been set up by the admin yet.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection