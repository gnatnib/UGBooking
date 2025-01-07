<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role_name' => 'admin',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567890',
            'position' => 'Administrator',
            'department' => 'IT',
        ]);

        // Create Sample User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567891',
            'position' => 'Staff',
            'department' => 'Marketing',
        ]);
    }
}