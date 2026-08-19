<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;

class Phase5TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainerRole = Role::where('slug', 'trainer')->first();

        // Create Trainer User
        $user = User::create([
            'name' => 'Coach Alex',
            'email' => 'coach@gym.com',
            'password' => Hash::make('password'),
            'role_id' => $trainerRole->id,
        ]);

        // Create Trainer Profile
        Trainer::create([
            'user_id' => $user->id,
            'specialization' => 'Weightlifting & Hiit',
            'bio' => 'Certified strength and conditioning specialist.',
            'phone' => '555-0199',
        ]);
    }
}