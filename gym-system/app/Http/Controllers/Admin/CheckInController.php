<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\CheckIn;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function scan($token)
    {
        // 1. Find the booking securely via the token
        $booking = Booking::with(['user', 'timeSlot', 'checkIn'])->where('qr_token', $token)->first();

        if (!$booking) {
            return view('admin.checkin.result', [
                'status' => 'error',
                'message' => 'Invalid or Unrecognized QR Code.'
            ]);
        }

        if ($booking->status === 'Cancelled') {
            return view('admin.checkin.result', [
                'status' => 'error',
                'message' => 'This booking was cancelled.'
            ]);
        }

        // 2. Validate Date (ensure they are checking in on the correct day)
        if (!$booking->timeSlot->date->isToday()) {
            return view('admin.checkin.result', [
                'status' => 'error',
                'message' => 'This booking is for a different date: ' . $booking->timeSlot->date->format('M d, Y')
            ]);
        }

        // 3. Process Check-Out (if they already checked in)
        if ($booking->checkIn && $booking->checkIn->check_in_time) {
            if ($booking->checkIn->check_out_time) {
                return view('admin.checkin.result', [
                    'status' => 'error',
                    'message' => 'User has already checked out for this session.'
                ]);
            }

            // Mark as checked out
            $booking->checkIn->update(['check_out_time' => Carbon::now()]);
            $booking->update(['status' => 'Completed']);

            return view('admin.checkin.result', [
                'status' => 'success',
                'action' => 'checkout',
                'user' => $booking->user,
                'message' => 'Check-out successful. Have a great day!'
            ]);
        }

        // 4. Process Check-In (First time scanning)
        CheckIn::create([
            'booking_id' => $booking->id,
            'check_in_time' => Carbon::now()
        ]);

        return view('admin.checkin.result', [
            'status' => 'success',
            'action' => 'checkin',
            'user' => $booking->user,
            'message' => 'Check-in successful. Welcome!'
        ]);
    }
}