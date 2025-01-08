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
            'fileupload' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Check for duplicate room type
            if (Room::where('room_type', $request->room_type)->exists()) {
                flash()->error('Room type already exists');
                return redirect()->back()->withInput();
            }

            // Handle file upload
            $photo = $request->fileupload;
            $fileName = time() . '_' . Str::slug($request->room_type) . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/rooms/'), $fileName);

            // Generate room ID
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

            // Create new room
            Room::create([
                'bkg_room_id' => $bkg_room_id,
                'room_type' => $request->room_type,
                'capacity' => $request->capacity,
                'fileupload' => $fileName,
                'status' => 'Ready',
                'has_projector' => $request->has('has_projector'),
                'has_sound_system' => $request->has('has_sound_system'),
                'has_tv' => $request->has('has_tv')
            ]);

            DB::commit();
            flash()->success('Room added successfully');
            return redirect()->route('form/allrooms/page');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving room: ' . $e->getMessage());
            flash()->error('Failed to add room');
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
    public function updateRecord(Request $request)
    {
        $request->validate([
            'room_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'fileupload' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Ready,Maintenance',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::where('bkg_room_id', $request->bkg_room_id)->firstOrFail();

            // Handle file upload if new file is provided
            if ($request->hasFile('fileupload')) {
                // Delete old file
                if ($room->fileupload) {
                    $oldFilePath = public_path('uploads/rooms/' . $room->fileupload);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                $photo = $request->fileupload;
                $fileName = time() . '_' . Str::slug($request->room_type) . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/rooms/'), $fileName);
            } else {
                $fileName = $room->fileupload;
            }

            // Update room
            $room->update([
                'room_type' => $request->room_type,
                'capacity' => $request->capacity,
                'fileupload' => $fileName,
                'status' => $request->status,
                'has_projector' => $request->has('has_projector'),
                'has_sound_system' => $request->has('has_sound_system'),
                'has_tv' => $request->has('has_tv')
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
            
            // Delete room image
            if ($room->fileupload) {
                $filePath = public_path('uploads/rooms/' . $room->fileupload);
                if (file_exists($filePath)) {
                    unlink($filePath);
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

    /** Get Room Details */
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
                'room' => $room
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