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

        // Definisikan data ruangan dengan multiple images
        $roomData = [
            [
                'room_type' => 'Ruang Meeting Besar',
                'bkg_room_id' => 'RMB-01',
                'capacity' => 20,
                'status' => 'Ready',
                'images' => ['RuangMeetingBesar_1.jpg', 'RuangMeetingBesar_2.jpg', 'RuangMeetingBesar_3.jpg'],
                'base_image' => 'RuangMeetingBesar.jpg',
                'facilities' => [
                    'LCD Projector',
                    'Sound System',
                    'TV',
                    'Whiteboard',
                    'Water Dispenser',
                    'Air Conditioning'
                ]
            ],
            [
                'room_type' => 'Ruang Meeting Kecil',
                'bkg_room_id' => 'RMK-01',
                'capacity' => 8,
                'status' => 'Ready',
                'images' => ['RuangMeetingKecil_1.jpg', 'RuangMeetingKecil_2.jpg', 'RuangMeetingKecil_3.jpg'],
                'base_image' => 'RuangMeetingKecil_3.jpg',
                'facilities' => [
                    'LCD Projector',
                    'TV',
                    'Whiteboard',
                    'Air Conditioning'
                ]
            ],
            [
                'room_type' => 'Ruang Diskusi',
                'bkg_room_id' => 'RD-01',
                'capacity' => 4,
                'status' => 'Ready',
                'images' => ['ruangdiskusi_1.jpg', 'ruangdiskusi_2.jpg', 'ruangdiskusi.jpg'],
                'base_image' => 'diskusi.jpg',
                'facilities' => [
                    'TV',
                    'Whiteboard',
                    'Air Conditioning'
                ]
            ]
        ];

        foreach ($roomData as $room) {
            // Copy file gambar utama
            if (File::exists(resource_path('images/' . $room['base_image']))) {
                // Copy setiap gambar dengan nama baru untuk multiple images
                foreach ($room['images'] as $index => $imageName) {
                    File::copy(
                        resource_path('images/' . $room['base_image']),
                        public_path('uploads/rooms/' . $imageName)
                    );
                }
            }

            // Buat record ruangan
            Room::create([
                'bkg_room_id' => $room['bkg_room_id'],
                'room_type' => $room['room_type'],
                'capacity' => $room['capacity'],
                'status' => $room['status'],
                'fileupload' => $room['base_image'],
                'images' => json_encode($room['images']),
                'facilities' => json_encode($room['facilities'])
            ]);
        }
    }
}
