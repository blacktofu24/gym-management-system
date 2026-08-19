<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gym Booking System</title>
    
    <!-- Load the correct Vite assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    
    <div class="text-center p-5 bg-white shadow rounded-3">
        <h1 class="display-4 fw-bold text-primary mb-3">
            <i class="fas fa-dumbbell"></i> Gym System
        </h1>
        <p class="lead text-muted mb-4">Welcome to your complete Gym Booking Management System.</p>
        
        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
            @auth
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4 gap-3">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 gap-3">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Register</a>
            @endauth
        </div>
    </div>

</body>
</html>