<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ruang Meeting Besar
        Room::create([
            'bkg_room_id' => 'RMB-01',
            'room_type' => 'Ruang Meeting Besar',
            'capacity' => 20,
            'status' => 'Ready',
            'has_projector' => true,
            'has_sound_system' => true,
            'has_tv' => true,
        ]);

        // Ruang Meeting Kecil
        Room::create([
            'bkg_room_id' => 'RMK-01',
            'room_type' => 'Ruang Meeting Kecil',
            'capacity' => 8,
            'status' => 'Ready',
            'has_projector' => true,
            'has_sound_system' => false,
            'has_tv' => true,
        ]);

        // Ruang Diskusi
        Room::create([
            'bkg_room_id' => 'RD-01',
            'room_type' => 'Ruang Diskusi',
            'capacity' => 4,
            'status' => 'Ready',
            'has_projector' => false,
            'has_sound_system' => false,
            'has_tv' => true,
        ]);
    }
}