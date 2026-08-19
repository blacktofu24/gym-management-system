<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\TrainerAvailability;
use App\Models\TrainerBooking;
use Illuminate\Support\Facades\Auth;

class MemberTrainerController extends Controller
{
    public function index()
    {
        // Load trainers with their future UNBOOKED availabilities
        $trainers = Trainer::with(['user', 'availabilities' => function($query) {
            $query->where('date', '>=', now()->toDateString())
                  ->where('is_booked', false)
                  ->orderBy('date')
                  ->orderBy('start_time');
        }])->get();

        $myTrainerBookings = TrainerBooking::with(['availability.trainer.user'])
            ->where('user_id', Auth::id())
            ->where('status', 'Scheduled')
            ->get();

        return view('member.trainers.index', compact('trainers', 'myTrainerBookings'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'availability_id' => 'required|exists:trainer_availabilities,id'
        ]);

        $availability = TrainerAvailability::findOrFail($request->availability_id);

        if ($availability->is_booked) {
            return back()->with('error', 'This slot is already booked.');
        }

        // Create booking
        TrainerBooking::create([
            'user_id' => Auth::id(),
            'trainer_availability_id' => $availability->id,
            'status' => 'Scheduled'
        ]);

        // Mark slot as booked
        $availability->update(['is_booked' => true]);

        return back()->with('success', 'Personal training session booked!');
    }
}