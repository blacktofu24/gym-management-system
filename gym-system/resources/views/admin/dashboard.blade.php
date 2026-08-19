@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Dashboard</h2>
        <div>
            <!-- Future management links can go here -->
            <button class="btn btn-outline-primary"><i class="fas fa-users"></i> Manage Members</button>
            <button class="btn btn-outline-secondary"><i class="fas fa-calendar-alt"></i> Gym Schedule</button>
        </div>
    </div>

    <!-- Top Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-users"></i> Total Members</h6>
                    <h2 class="display-5 fw-bold mb-0">{{ $totalMembers }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-id-card"></i> Active Memberships</h6>
                    <h2 class="display-5 fw-bold mb-0">{{ $activeMemberships }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-calendar-check"></i> Today's Bookings</h6>
                    <h2 class="display-5 fw-bold mb-0">{{ $todaysBookings }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning text-dark shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-qrcode"></i> Today's Check-ins</h6>
                    <h2 class="display-5 fw-bold mb-0">{{ $todaysCheckIns }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Section -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Booking Trends (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="bookingsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">System Overview</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        Total Trainers
                        <span class="badge bg-primary rounded-pill fs-6">{{ $totalTrainers }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        Cancelled Bookings (All Time)
                        <span class="badge bg-danger rounded-pill fs-6">{{ $cancelledBookings }}</span>
                    </li>
                    <li class="list-group-item p-3 text-center bg-light">
                        <small class="text-muted">More detailed reports coming in next phases.</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('bookingsChart').getContext('2d');
        
        // Data passed from Laravel Controller
        const labels = {!! json_encode($chartDates) !!};
        const data = {!! json_encode($chartBookings) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Bookings',
                    data: data,
                    borderColor: 'rgba(13, 110, 253, 1)', 
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderWidth: 2,
                    tension: 0.3, 
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 
                        }
                    }
                }
            }
        });
    });
</script>
@endsection