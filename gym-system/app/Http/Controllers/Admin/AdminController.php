<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\Trainer;
use App\Models\Booking;
use App\Models\CheckIn;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // 1. Calculate Statistics for Top Cards
        $totalMembers = User::whereHas('role', function($q) { 
            $q->where('slug', 'member'); 
        })->count();

        $activeMemberships = Membership::where('status', 'Active')
            ->where('end_date', '>=', $today)
            ->count();

        $totalTrainers = Trainer::count();

        $todaysBookings = Booking::whereHas('timeSlot', function($q) use ($today) {
            $q->where('date', $today->toDateString());
        })->where('status', 'Booked')->count();

        $todaysCheckIns = CheckIn::whereDate('check_in_time', $today)->count();
        
        $cancelledBookings = Booking::where('status', 'Cancelled')->count();

        // 2. Prepare Data for Chart.js (Bookings over the last 7 days)
        $chartDates = [];
        $chartBookings = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('M d'); // e.g., "Aug 12"
            
            $count = Booking::whereHas('timeSlot', function($q) use ($date) {
                $q->where('date', $date->toDateString());
            })->count();
            
            $chartBookings[] = $count;
        }

        return view('admin.dashboard', compact(
            'totalMembers', 
            'activeMemberships', 
            'totalTrainers',
            'todaysBookings', 
            'todaysCheckIns', 
            'cancelledBookings',
            'chartDates',
            'chartBookings'
        ));
    }
}