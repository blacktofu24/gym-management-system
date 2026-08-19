<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\Waitlist;
use App\Models\AuditLog;
use App\Notifications\SlotAvailableNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership;

        $timeSlots = TimeSlot::withCount(['bookings' => function($query) {
            $query->where('status', 'Booked');
        }])
        ->where('date', '>=', now()->toDateString())
        ->orderBy('date')
        ->orderBy('start_time')
        ->get();

        $myBookings = Booking::with('timeSlot')
            ->where('user_id', $user->id)
            ->whereIn('status', ['Booked', 'Completed'])
            ->whereHas('timeSlot', function($query) {
                $query->where('date', '>=', now()->toDateString());
            })
            ->orderByDesc('created_at')
            ->get();

        // Get IDs of slots the user is waitlisting for
        $myWaitlists = Waitlist::where('user_id', $user->id)
            ->whereIn('status', ['waiting', 'notified'])
            ->pluck('time_slot_id')->toArray();

        return view('member.bookings.index', compact('timeSlots', 'myBookings', 'activeMembership', 'myWaitlists'));
    }

    public function store(Request $request)
    {
        $request->validate(['time_slot_id' => 'required|exists:time_slots,id']);
        $user = Auth::user();

        if (!$user->activeMembership) {
            return back()->with('error', 'You must have an active membership to book a slot.');
        }

        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
        $existingBooking = Booking::where('user_id', $user->id)->where('time_slot_id', $timeSlot->id)->where('status', 'Booked')->first();

        if ($existingBooking) {
            return back()->with('error', 'You have already booked this time slot.');
        }

        $currentOccupancy = $timeSlot->bookings()->where('status', 'Booked')->count();
        
        if ($currentOccupancy >= $timeSlot->max_capacity) {
            return back()->with('error', 'This time slot is FULL.');
        }

        Booking::create([
            'user_id' => $user->id,
            'time_slot_id' => $timeSlot->id,
            'status' => 'Booked',
            'qr_token' => Str::random(40)
        ]);

        AuditLog::log('Booking Created', "Booked slot ID {$timeSlot->id}");

        return back()->with('success', 'Your gym slot has been booked successfully!');
    }

    public function cancel($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $booking->update(['status' => 'Cancelled']);

        AuditLog::log('Booking Cancelled', "Cancelled booking ID {$booking->id}");

        // --- WAITLIST LOGIC ---
        // Find the first person waiting for this slot
        $nextInLine = Waitlist::where('time_slot_id', $booking->time_slot_id)
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->first();

        if ($nextInLine) {
            $nextInLine->update(['status' => 'notified']);
            $nextInLine->user->notify(new SlotAvailableNotification($booking->timeSlot, $nextInLine->id));
        }

        return back()->with('success', 'Your booking has been cancelled.');
    }

    public function joinWaitlist(Request $request)
    {
        $request->validate(['time_slot_id' => 'required|exists:time_slots,id']);
        
        Waitlist::firstOrCreate([
            'user_id' => Auth::id(),
            'time_slot_id' => $request->time_slot_id,
            'status' => 'waiting'
        ]);

        AuditLog::log('Joined Waitlist', "Joined waitlist for slot ID {$request->time_slot_id}");

        return back()->with('success', 'You have been added to the waitlist. We will notify you if a spot opens up!');
    }

    public function confirmWaitlist($id)
    {
        $waitlist = Waitlist::where('id', $id)->where('user_id', Auth::id())->where('status', 'notified')->firstOrFail();
        $timeSlot = $waitlist->timeSlot;

        $currentOccupancy = $timeSlot->bookings()->where('status', 'Booked')->count();
        if ($currentOccupancy >= $timeSlot->max_capacity) {
            return redirect()->route('member.bookings.index')->with('error', 'Sorry, the slot filled up again.');
        }

        Booking::create([
            'user_id' => Auth::id(),
            'time_slot_id' => $timeSlot->id,
            'status' => 'Booked',
            'qr_token' => Str::random(40)
        ]);

        $waitlist->update(['status' => 'booked']);
        
        // Mark notification as read
        Auth::user()->unreadNotifications->where('data.waitlist_id', $id)->markAsRead();

        AuditLog::log('Waitlist Claimed', "Claimed waitlist slot ID {$timeSlot->id}");

        return redirect()->route('member.bookings.index')->with('success', 'Waitlist spot claimed successfully!');
    }
}