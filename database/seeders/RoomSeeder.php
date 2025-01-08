<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Illuminate\Support\Facades\File;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // Buat direktori jika belum ada
        $uploadPath = public_path('uploads/rooms');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true);
        }

        // Copy file gambar dari folder resources ke public/uploads/rooms
        $sourceImages = [
            'RuangMeetingBesar.jpg',
            'RuangMeetingKecil.jpeg',
            'diskusi.jpg'
        ];

        foreach ($sourceImages as $image) {
            if (File::exists(resource_path('images/' . $image))) {
                File::copy(
                    resource_path('images/' . $image),
                    public_path('uploads/rooms/' . $image)
                );
            }
        }

        // Ruang Meeting Besar
        Room::create([
            'bkg_room_id' => 'RMB-01',
            'room_type' => 'Ruang Meeting Besar',
            'capacity' => 20,
            'status' => 'Ready',
            'has_projector' => true,
            'has_sound_system' => true,
            'has_tv' => true,
            'fileupload' => 'RuangMeetingBesar.jpg'
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
            'fileupload' => 'RuangMeetingKecil.jpeg'
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
            'fileupload' => 'diskusi.jpg'
        ]);
    }
}