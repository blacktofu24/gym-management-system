<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\Membership;
use App\Models\TimeSlot;
use Carbon\Carbon;

class Phase3TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Give your existing test user an Active Membership
        $user = User::where('role_id', 3)->first(); // Gets the first member account
        
        if ($user && !$user->activeMembership) {
            $plan = MembershipPlan::create([
                'name' => 'Premium Pass', 
                'price' => 1500.00, 
                'duration_in_days' => 30,
                'description' => 'Unlimited gym access'
            ]);

            Membership::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'status' => 'Active',
                'payment_status' => 'Paid'
            ]);
        }

        // 2. Generate Time Slots for Today and Tomorrow
        $days = [Carbon::now(), Carbon::now()->addDay()];
        
        foreach ($days as $day) {
            TimeSlot::create([
                'date' => $day->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'max_capacity' => 30
            ]);
            
            TimeSlot::create([
                'date' => $day->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'max_capacity' => 1 // Setting to 1 to easily test the FULL capacity logic
            ]);
            
            TimeSlot::create([
                'date' => $day->toDateString(),
                'start_time' => '17:00:00',
                'end_time' => '19:00:00',
                'max_capacity' => 30
            ]);
        }
    }
}