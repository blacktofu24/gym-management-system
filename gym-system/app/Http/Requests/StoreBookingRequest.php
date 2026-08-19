<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow members to submit this form
        return Auth::check() && Auth::user()->isMember();
    }

    public function rules(): array
    {
        return [
            'time_slot_id' => [
                'required',
                'integer',
                'exists:time_slots,id' // Ensures the slot actually exists in the database
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot_id.required' => 'Please select a valid time slot.',
            'time_slot_id.exists' => 'The selected time slot is invalid or no longer exists.',
        ];
    }
}