<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $trainerRole = Role::create(['name' => 'Trainer', 'slug' => 'trainer']);
        $memberRole = Role::create(['name' => 'Member', 'slug' => 'member']);

        // Create Default Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@gym.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);
    }
}