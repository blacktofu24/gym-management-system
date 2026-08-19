<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership;
        
        // Placeholders for Phase 3 & 4 data
        $upcomingBookings = collect([]); 
        $currentOccupancy = 0;
        $maxCapacity = 30;

        return view('member.dashboard', compact(
            'user', 
            'activeMembership', 
            'upcomingBookings', 
            'currentOccupancy', 
            'maxCapacity'
        ));
    }
}