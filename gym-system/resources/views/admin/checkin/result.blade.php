@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg text-center" style="width: 100%; max-width: 500px;">
        <div class="card-body p-5">
            
            @if($status === 'success')
                
                @if($action === 'checkin')
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    <h2 class="mt-4 text-success">✅ Check-in Successful</h2>
                @else
                    <i class="fas fa-sign-out-alt text-info" style="font-size: 5rem;"></i>
                    <h2 class="mt-4 text-info">👋 Check-out Successful</h2>
                @endif
                
                <h4 class="mt-3">Member: <strong>{{ $user->name }}</strong></h4>
                <p class="lead text-muted mt-3">{{ $message }}</p>
                
            @else
                <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                <h2 class="mt-4 text-danger">Action Denied</h2>
                <p class="lead text-muted mt-3">{{ $message }}</p>
            @endif

            <hr class="my-4">
            
            <a href="{{ route('admin.dashboard') }}" class="btn btn-lg btn-secondary">Return to Dashboard</a>

        </div>
    </div>
</div>
@endsection