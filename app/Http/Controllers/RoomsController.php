<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    /** Index Page - All Rooms */
    public function allrooms()
    {
        try {
            $allRooms = Room::all();
            return view('room.allroom', compact('allRooms'));
        } catch (\Exception $e) {
            Log::error('Error fetching rooms: ' . $e->getMessage());
            flash()->error('Failed to load rooms');
            return redirect()->back();
        }
    }

    /** Add Room Page */
    public function addRoom()
    {
        try {
            $data = DB::table('room_types')->get();
            return view('room.addroom', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error loading add room page: ' . $e->getMessage());
            flash()->error('Failed to load add room page');
            return redirect()->back();
        }
    }

    /** Save Room Record */
    public function saveRecordRoom(Request $request)
    {
        $request->validate([
            'room_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'room_images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'facilities' => 'required|array',
            'facilities.*' => 'required|string|max:100'
        ]);
    
        DB::beginTransaction();
        try {
            // Check for duplicate room type
            if (Room::where('room_type', $request->room_type)->exists()) {
                flash()->error('Room type already exists');
                return redirect()->back()->withInput();
            }
    
            // Handle multiple images
            $imageNames = [];
            if($request->hasFile('room_images')) {
                foreach($request->file('room_images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/rooms/'), $imageName);
                    $imageNames[] = $imageName;
                }
            }
    
            // Get the first image as the main fileupload
            $mainImage = isset($imageNames[0]) ? $imageNames[0] : null;
    
            // Generate room ID based on room type
            $prefix = '';
            switch($request->room_type) {
                case 'Ruang Meeting Besar':
                    $prefix = 'RMB';
                    break;
                case 'Ruang Meeting Kecil':
                    $prefix = 'RMK';
                    break;
                case 'Ruang Diskusi':
                    $prefix = 'RD';
                    break;
                default:
                    $prefix = 'RM';
            }
    
            $lastRoom = Room::where('bkg_room_id', 'like', $prefix.'%')
                           ->orderBy('bkg_room_id', 'desc')
                           ->first();
            
            $number = $lastRoom ? (int)substr($lastRoom->bkg_room_id, -2) + 1 : 1;
            $bkg_room_id = $prefix . '-' . str_pad($number, 2, '0', STR_PAD_LEFT);
    
            // Create new room with both old and new structure
            Room::create([
                'bkg_room_id' => $bkg_room_id,
                'room_type' => $request->room_type,
                'capacity' => $request->capacity,
                'fileupload' => $mainImage, // Maintain backward compatibility
                'images' => json_encode($imageNames), // New multiple images
                'facilities' => json_encode($request->facilities), // New custom facilities
                'status' => 'Ready',
                // Maintain backward compatibility for boolean facilities
                'has_projector' => in_array('LCD Projector', $request->facilities),
                'has_sound_system' => in_array('Sound System', $request->facilities),
                'has_tv' => in_array('TV', $request->facilities)
            ]);
    
            DB::commit();
            flash()->success('Room added successfully');
            return redirect()->route('form/allrooms/page');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving room: ' . $e->getMessage());
            flash()->error('Failed to add room: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /** Edit Room View */
    public function editRoom($bkg_room_id)
    {
        try {
            $roomEdit = Room::where('bkg_room_id', $bkg_room_id)->firstOrFail();
            $data = DB::table('room_types')->get();
            return view('room.editroom', compact('data', 'roomEdit'));
        } catch (\Exception $e) {
            Log::error('Error loading edit room: ' . $e->getMessage());
            flash()->error('Room not found');
            return redirect()->route('form/allrooms/page');
        }
    }

    /** Update Room Record */
 /** Update Room Record */
 public function updateRecord(Request $request)
 {
     $request->validate([
         'room_type' => 'required|string|max:255',
         'capacity' => 'required|integer|min:1|max:50',
         'room_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
         'facilities' => 'required|array',
         'facilities.*' => 'required|string|max:100',
         'status' => 'required|in:Ready,Maintenance',
     ]);
 
     DB::beginTransaction();
     try {
         $room = Room::where('bkg_room_id', $request->bkg_room_id)->firstOrFail();
 
         // Handle images
         $currentImages = json_decode($room->images) ?? [];
         $removedImages = json_decode($request->removed_images) ?? [];
         $newImages = [];
 
         // Remove deleted images
         foreach ($removedImages as $image) {
             $imagePath = public_path('uploads/rooms/' . $image);
             if (file_exists($imagePath)) {
                 unlink($imagePath);
             }
             $currentImages = array_diff($currentImages, [$image]);
         }
 
         // Add new images
         if($request->hasFile('room_images')) {
             foreach($request->file('room_images') as $image) {
                 $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                 $image->move(public_path('uploads/rooms/'), $imageName);
                 $newImages[] = $imageName;
             }
         }
 
         // Combine current and new images
         $finalImages = array_merge($currentImages, $newImages);
 
         // Update room
         $room->update([
             'room_type' => $request->room_type,
             'capacity' => $request->capacity,
             'images' => json_encode($finalImages),
             'facilities' => json_encode($request->facilities),
             'status' => $request->status
         ]);
 
         DB::commit();
         flash()->success('Room updated successfully');
         return redirect()->route('form/allrooms/page');
     } catch (\Exception $e) {
         DB::rollback();
         Log::error('Error updating room: ' . $e->getMessage());
         flash()->error('Failed to update room');
         return redirect()->back()->withInput();
     }
 }
    
    /** Delete Room Record */
    public function deleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $room = Room::findOrFail($request->id);
            
            // Delete all room images
            if ($room->images) {
                foreach(json_decode($room->images) as $image) {
                    $filePath = public_path('uploads/rooms/' . $image);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
    
            $room->delete();
            
            DB::commit();
            flash()->success('Room deleted successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting room: ' . $e->getMessage());
            flash()->error('Failed to delete room');
            return redirect()->back();
        }
    }

    /** Add Room Type */
    public function addRoomType(Request $request)
    {
        try {
            $request->validate([
                'new_room_type' => 'required|string|max:255|unique:room_types,room_name'
            ]);

            DB::table('room_types')->insert([
                'room_name' => $request->new_room_type,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Room type added successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding room type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add room type'
            ], 500);
        }
    }

    /** Delete Room Type */
    public function deleteRoomType(Request $request)
    {
        try {
            $request->validate([
                'room_type_id' => 'required|exists:room_types,id'
            ]);

            $roomsUsingType = Room::where('room_type', function ($query) use ($request) {
                $query->select('room_name')
                    ->from('room_types')
                    ->where('id', $request->room_type_id);
            })->exists();

            if ($roomsUsingType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete room type as it is being used by existing rooms'
                ], 422);
            }

            DB::table('room_types')->where('id', $request->room_type_id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Room type deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting room type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room type'
            ], 500);
        }
    }

    /** Get Room Details //* Get Room Details */
    public function getRoomDetails($roomType)
    {
        try {
            $room = Room::where('room_type', $roomType)
                ->where('status', 'Ready')
                ->first();
    
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'room' => [
                    'room_type' => $room->room_type,
                    'capacity' => $room->capacity,
                    'images' => $room->images,
                    'facilities' => $room->facilities
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching room details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching room details'
            ], 500);
        }
    }
}