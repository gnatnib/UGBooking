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
        // Building Management Division
        User::create([
            'user_id' => 'BM001',
            'name' => 'Budi Santoso',
            'email' => 'budisantoso@example.com',
            'password' => Hash::make('budi123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567801',
            'division' => 'Building Management',
            'department' => 'Maintenance',
        ]);

        User::create([
            'user_id' => 'BM002',
            'name' => 'Dewi Pratiwi',
            'email' => 'dewipratiwi@example.com',
            'password' => Hash::make('dewi123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567802',
            'division' => 'Building Management',
            'department' => 'Operations',
        ]);

        User::create([
            'user_id' => 'BM003',
            'name' => 'Agus Firmansyah',
            'email' => 'agusfirmansyah@example.com',
            'password' => Hash::make('agus123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567803',
            'division' => 'Building Management',
            'department' => 'Security',
        ]);

        // Construction and Property Division
        User::create([
            'user_id' => 'CP001',
            'name' => 'Rini Wijaya',
            'email' => 'riniwijaya@example.com',
            'password' => Hash::make('rini123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567804',
            'division' => 'Construction and Property',
            'department' => 'Project Management',
        ]);

        User::create([
            'user_id' => 'CP002',
            'name' => 'Dedi Kurniawan',
            'email' => 'dedikurniawan@example.com',
            'password' => Hash::make('dedi123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567805',
            'division' => 'Construction and Property',
            'department' => 'Construction',
        ]);

        User::create([
            'user_id' => 'CP003',
            'name' => 'Siti Rahayu',
            'email' => 'sitirahayu@example.com',
            'password' => Hash::make('siti123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567806',
            'division' => 'Construction and Property',
            'department' => 'Property',
        ]);

        // IT Business and Solution Division
        User::create([
            'user_id' => 'ITBS01',
            'name' => 'Hendro Wicaksono',
            'email' => 'hendrowicaksono@example.com',
            'password' => Hash::make('hendro123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567807',
            'division' => 'IT Business and Solution',
            'department' => 'Development',
        ]);

        User::create([
            'user_id' => 'ITBS02',
            'name' => 'Maya Putri',
            'email' => 'mayaputri@example.com',
            'password' => Hash::make('maya123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567808',
            'division' => 'IT Business and Solution',
            'department' => 'Infrastructure',
        ]);

        User::create([
            'user_id' => 'ITBS03',
            'name' => 'Rizki Pratama',
            'email' => 'rizkipratama@example.com',
            'password' => Hash::make('rizki123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567809',
            'division' => 'IT Business and Solution',
            'department' => 'Support',
        ]);

        // Finance and Accounting Division
        User::create([
            'user_id' => 'FA001',
            'name' => 'Linda Kusuma',
            'email' => 'lindakusuma@example.com',
            'password' => Hash::make('linda123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567810',
            'division' => 'Finance and Accounting',
            'department' => 'Finance',
        ]);

        User::create([
            'user_id' => 'FA002',
            'name' => 'Anton Wijaya',
            'email' => 'antonwijaya@example.com',
            'password' => Hash::make('anton123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567811',
            'division' => 'Finance and Accounting',
            'department' => 'Accounting',
        ]);

        User::create([
            'user_id' => 'FA003',
            'name' => 'Nina Safitri',
            'email' => 'ninasafitri@example.com',
            'password' => Hash::make('nina123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567812',
            'division' => 'Finance and Accounting',
            'department' => 'Tax',
        ]);

        // Human Capital and General Affair Division
        User::create([
            'user_id' => 'HC001',
            'name' => 'Bambang Hermawan',
            'email' => 'bambanghermawan@example.com',
            'password' => Hash::make('bambang123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567813',
            'division' => 'Human Capital and General Affair',
            'department' => 'Recruitment',
        ]);

        User::create([
            'user_id' => 'HC002',
            'name' => 'Diana Puspita',
            'email' => 'dianapuspita@example.com',
            'password' => Hash::make('diana123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567814',
            'division' => 'Human Capital and General Affair',
            'department' => 'Training',
        ]);

        User::create([
            'user_id' => 'HC003',
            'name' => 'Faisal Rahman',
            'email' => 'faisalrahman@example.com',
            'password' => Hash::make('faisal123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567815',
            'division' => 'Human Capital and General Affair',
            'department' => 'General Affair',
        ]);

        // Risk, System, and Compliance Division
        User::create([
            'user_id' => 'RSC01',
            'name' => 'Yuni Hartanti',
            'email' => 'yunihartanti@example.com',
            'password' => Hash::make('yuni123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567816',
            'division' => 'Risk, System, and Compliance',
            'department' => 'Risk Management',
        ]);

        User::create([
            'user_id' => 'RSC02',
            'name' => 'Hadi Sulistyo',
            'email' => 'hadisulistyo@example.com',
            'password' => Hash::make('hadi123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567817',
            'division' => 'Risk, System, and Compliance',
            'department' => 'Compliance',
        ]);

        User::create([
            'user_id' => 'RSC03',
            'name' => 'Rina Wulandari',
            'email' => 'rinawulandari@example.com',
            'password' => Hash::make('rina123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567818',
            'division' => 'Risk, System, and Compliance',
            'department' => 'System',
        ]);

        // Internal Audit Division
        User::create([
            'user_id' => 'IA001',
            'name' => 'Tono Prasetyo',
            'email' => 'tonoprasetyo@example.com',
            'password' => Hash::make('tono123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567819',
            'division' => 'Internal Audit',
            'department' => 'Financial Audit',
        ]);

        User::create([
            'user_id' => 'IA002',
            'name' => 'Sri Wahyuni',
            'email' => 'sriwahyuni@example.com',
            'password' => Hash::make('sri123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567820',
            'division' => 'Internal Audit',
            'department' => 'Operational Audit',
        ]);

        User::create([
            'user_id' => 'IA003',
            'name' => 'Adi Nugroho',
            'email' => 'adinugroho@example.com',
            'password' => Hash::make('adi123'),
            'role_name' => 'user',
            'status' => 'Active',
            'join_date' => now()->format('Y-m-d'),
            'phone_number' => '081234567821',
            'division' => 'Internal Audit',
            'department' => 'IT Audit',
        ]);
    }
}
