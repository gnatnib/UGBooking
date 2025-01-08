<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Superadmin
        User::create([
            'user_id' => 'ITBS01',
            'name' => 'Superadmin Mahfud',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role_name' => 'superadmin',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '085123123123',
            'division' => 'IT Business and Solution',
            'department' => 'IT',
        ]);
        // Create Admin
        User::create([
            'user_id' => 'HCGA01',
            'name' => 'Admin Sarah',
            'email' => 'admin1@example.com',
            'password' => Hash::make('admin123'),
            'role_name' => 'admin',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '089827182777',
            'division' => 'Human Capital and General Affair',
            'department' => 'HR',
        ]);
        User::create([
            'user_id' => 'HCGA02',
            'name' => 'Admin Hannah',
            'email' => 'admin2@example.com',
            'password' => Hash::make('admin123'),
            'role_name' => 'admin',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567890',
            'division' => 'Human Capital and General Affair',
            'department' => 'HR',
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
            'division' => 'Human Capital and General Affair',
            'department' => 'HR',
        ]);
    }
}
