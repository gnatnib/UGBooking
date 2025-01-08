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
            'user_id' => 'ITBS01',
            'name' => 'Admin Reko',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role_name' => 'admin',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567890',
            'division' => 'IT Business and Solution',
            'department' => 'IT',
        ]);
    
        // Create Sample User
        User::create([
            'user_id' => 'ITBS02',
            'name' => 'Erni Indah',
            'email' => 'erniindah@example.com',
            'password' => Hash::make('erni123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567891',
            'division' => 'IT Business and Solution',
            'department' => 'Human Resources',
        ]);
    }
}
