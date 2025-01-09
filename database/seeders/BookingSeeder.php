<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users = \App\Models\User::all();
        $rooms = \App\Models\Room::all();
        
        $bookings = [
            [
                'bkg_id' => 'BKG-' . str_pad(1, 5, '0', STR_PAD_LEFT),
                'name' => $users->random()->name,
                'room_type' => $rooms->random()->room_type,
                'total_numbers' => 5,
                'date' => Carbon::now()->format('Y-m-d'),
                'time_start' => '09:00',
                'time_end' => '11:00',
                'email' => 'hadisulistyo@example.com',
                'phone_number' => '081234567817',
                'message' => 'Weekly Team Meeting',
                'status_meet' => 'Finished',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bkg_id' => 'BKG-' . str_pad(2, 5, '0', STR_PAD_LEFT),
                'name' => $users->random()->name,
                'room_type' => $rooms->random()->room_type,
                'total_numbers' => 8,
                'date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'time_start' => '13:00',
                'time_end' => '15:00',
                'email' => 'rizkipratama@example.com',
                'phone_number' => '081234567809',
                'message' => 'Project Presentation',
                'status_meet' => 'Booked',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'bkg_id' => 'BKG-' . str_pad(3, 5, '0', STR_PAD_LEFT),
                'name' => $users->random()->name,
                'room_type' => $rooms->random()->room_type,
                'total_numbers' => 12,
                'date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'time_start' => '10:00',
                'time_end' => '12:00',
                'email' => 'mayaputri@example.com',
                'phone_number' => '081234567808',
                'message' => 'Department Meeting',
                'status_meet' => 'Booked',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}