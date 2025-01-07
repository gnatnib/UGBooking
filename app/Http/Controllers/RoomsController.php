<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomsController extends Controller
{
    /** Index Page */
    public function allrooms()
    {
        $allRooms = DB::table('rooms')->get();
        return view('room.allroom', compact('allRooms'));
    }

    /** Room Page */
    public function addRoom()
    {
        $data = DB::table('room_types')->get();
        $user = DB::table('users')->get();
        return view('room.addroom', compact('user', 'data'));
    }

    /** View Record */
    public function editRoom($bkg_room_id)
    {
        $roomEdit = DB::table('rooms')->where('bkg_room_id', $bkg_room_id)->first();
        $data = DB::table('room_types')->get();
        $user = DB::table('users')->get();
        return view('room.editroom', compact('user', 'data', 'roomEdit'));
    }

    /** Save Record */
    public function saveRecordRoom(Request $request)
    {
        $request->validate([
            'room_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'fileupload' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $photo = $request->fileupload;
            $file_name = rand() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('/assets/upload/'), $file_name);

            $room = new Room;
            $room->room_type = $request->room_type;
            $room->capacity = $request->capacity;
            $room->fileupload = $file_name;
            $room->has_projector = $request->has('has_projector');
            $room->has_sound_system = $request->has('has_sound_system');
            $room->has_tv = $request->has('has_tv');
            $room->save();

            DB::commit();
            flash()->success('Meeting room created successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            flash()->error('Failed to add meeting room');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!empty($request->fileupload)) {
                $photo = $request->fileupload;
                $file_name = rand() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/assets/upload/'), $file_name);
            } else {
                $file_name = $request->hidden_fileupload;
            }

            $update = [
                'bkg_room_id'              => $request->bkg_room_id,
                'name'                     => $request->name,
                'room_type'                => $request->room_type,
                'ac_non_ac'                => $request->ac_non_ac,
                'food'                     => $request->food,
                'bed_count'                => $request->bed_count,
                'charges_for_cancellation' => $request->charges_for_cancellation,
                'phone_number'             => $request->phone_number,
                'fileupload'               => $file_name,
                'message'                  => $request->message,
            ];
            Room::where('bkg_room_id', $request->bkg_room_id)->update($update);

            DB::commit();
            flash()->error('Updated room successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            flash()->error('Update room fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecord(Request $request)
    {
        try {
            Room::destroy($request->id);
            unlink('assets/upload/' . $request->fileupload);
            flash()->success('Room deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            flash()->error('Room delete fail :)');
            return redirect()->back();
        }
    }

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
    public function deleteRoomType(Request $request)
    {
        try {
            $request->validate([
                'room_type_id' => 'required|exists:room_types,id'
            ]);

            // Check if the room type is in use
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
            \Log::error('Error deleting room type: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room type'
            ], 500);
        }
    }
}
