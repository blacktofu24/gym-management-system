<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'description'];

    // Helper function to easily log actions anywhere in the app
    public static function log(string $action, string $description)
    {
        self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}