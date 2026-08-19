<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Added qr_token here
    protected $fillable = ['user_id', 'time_slot_id', 'status', 'qr_token']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function checkIn()
    {
        return $this->hasOne(CheckIn::class);
    }
}