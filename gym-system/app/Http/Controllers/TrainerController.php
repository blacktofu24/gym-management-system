<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainerAvailability;
use App\Models\TrainerBooking;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $trainer = Auth::user()->trainerProfile;

        // Get upcoming sessions booked by members
        $upcomingSessions = TrainerBooking::with(['user', 'availability'])
            ->whereHas('availability', function($query) use ($trainer) {
                $query->where('trainer_id', $trainer->id)
                      ->where('date', '>=', now()->toDateString());
            })
            ->where('status', 'Scheduled')
            ->orderBy(TrainerAvailability::select('date')->whereColumn('trainer_availabilities.id', 'trainer_bookings.trainer_availability_id'))
            ->get();

        // Get trainer's current availability slots
        $availabilities = TrainerAvailability::where('trainer_id', $trainer->id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('trainer.dashboard', compact('upcomingSessions', 'availabilities'));
    }

    public function storeAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $trainer = Auth::user()->trainerProfile;

        TrainerAvailability::create([
            'trainer_id' => $trainer->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_booked' => false,
        ]);

        return back()->with('success', 'Availability added successfully!');
    }
}