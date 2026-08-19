<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'date_of_birth',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}