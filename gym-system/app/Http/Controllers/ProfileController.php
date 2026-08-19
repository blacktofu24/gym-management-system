<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $member = $user->memberProfile; 
        
        return view('member.profile.edit', compact('user', 'member'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        
        // Update User table
        $user->update([
            'name' => $request->name
        ]);

        // Update Member table
        $user->memberProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('phone', 'date_of_birth', 'address', 'emergency_contact_name', 'emergency_contact_phone')
        );

        return back()->with('success', 'Profile updated successfully!');
    }
}