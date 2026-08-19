<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPlan;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        MembershipPlan::insert([
            [
                'name' => 'Monthly Pass',
                'price' => 1500.00,
                'duration_in_days' => 30,
                'description' => 'Perfect for staying committed month-to-month.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quarterly Pro',
                'price' => 4000.00,
                'duration_in_days' => 90,
                'description' => 'Save money by committing to 3 months of fitness.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Annual Elite',
                'price' => 12000.00,
                'duration_in_days' => 365,
                'description' => 'Our best value. A full year of unlimited gym access.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}