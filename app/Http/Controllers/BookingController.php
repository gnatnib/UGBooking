<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
/** Save Record */


use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BookingController extends Controller
{

    public function updateBookingStatus()
{
    try {
        $now = Carbon::now();
        $currentDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        // Update bookings to 'Booked' if meeting has not started yet
        Booking::where('date', $currentDate)
            ->where('time_start', '>', $currentTime)
            ->where('status_meet', '!=', 'Booked')
            ->update(['status_meet' => 'Booked']);

        // Update bookings that are currently in progress to 'In meeting'
        Booking::where('date', $currentDate)
            ->where('time_start', '<=', $currentTime)
            ->where('time_end', '>', $currentTime)
            ->where('status_meet', '!=', 'In meeting')
            ->update(['status_meet' => 'In meeting']);

        // Update bookings that have ended to 'Finished'
        Booking::where(function ($query) use ($currentDate, $currentTime) {
            $query->where('date', '<', $currentDate)
                ->orWhere(function ($q) use ($currentDate, $currentTime) {
                    $q->where('date', '=', $currentDate)
                        ->where('time_end', '<=', $currentTime);
                });
        })
        ->where('status_meet', '!=', 'Finished')
        ->update(['status_meet' => 'Finished']);

        return true;
    } catch (\Exception $e) {
        Log::error('Error updating booking statuses: ' . $e->getMessage());
        return false;
    }
}
public function endMeeting(Request $request)
{
    try {
        DB::beginTransaction();
        
        // Log incoming request
        Log::info('End Meeting request received:', [
            'booking_id' => $request->id
        ]);

        // Find booking using model
        $booking = Booking::where('bkg_id', $request->id)->first();
        
        if (!$booking) {
            DB::rollBack();
            Log::error('Booking not found:', ['id' => $request->id]);
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        // Get current time
        $currentTime = Carbon::now();
        
        // Update both status and end time
        $booking->status_meet = 'Finished';
        $booking->time_end = $currentTime->format('H:i:s');
        $result = $booking->save();

        // Log after update
        Log::info('Update attempt result:', [
            'booking_id' => $booking->bkg_id,
            'save_result' => $result,
            'new_status' => $booking->status_meet,
            'new_end_time' => $booking->time_end
        ]);

        if ($result) {
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Meeting ended successfully at ' . $currentTime->format('H:i'),
                'debug_info' => [
                    'booking_id' => $booking->bkg_id,
                    'final_status' => $booking->status_meet,
                    'end_time' => $booking->time_end
                ]
            ]);
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update meeting status'
            ]);
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('End Meeting Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to end the meeting: ' . $e->getMessage()
        ]);
    }
}
    
    /** View Page All */
    public function allbooking()
{
    // Update booking statuses
    $this->updateBookingStatus();
    
    $user = Auth::user();
    
    if ($user->role_name === 'admin' || $user->role_name === 'superadmin') {
        $allBookings = DB::table('bookings')
            ->orderBy('date', 'desc')
            ->orderBy('time_start', 'desc')
            ->get();
    } else {
        $allBookings = DB::table('bookings')
            ->where('name', $user->name)
            ->orderBy('date', 'desc')
            ->orderBy('time_start', 'desc')
            ->get();
    }
    
    return view('formbooking.allbooking', compact('allBookings'));
}

    /** Page */
    public function bookingAdd()
    {
        $currentUser = Auth::user(); // Mendapatkan pengguna yang sedang autentikasi
        
        // Jika pengguna adalah admin atau superadmin, tampilkan semua pengguna
        if ($currentUser->role_name === 'admin' || $currentUser->role_name === 'superadmin') {
            $user = DB::table('users')->get();
        } else {
            // Jika pengguna biasa, hanya tampilkan data mereka sendiri
            $user = DB::table('users')
                ->where('name', $currentUser->name)
                ->get();
        }
        
        $data = DB::table('rooms')->select('room_type')->where('status', 'Ready')->distinct()->get();
       
        return view('formbooking.bookingadd', compact('data', 'user'));
    }
    

    /** View Record */
    public function bookingEdit($bkg_id)
    {
        $bookingEdit = DB::table('bookings')->where('bkg_id', $bkg_id)->first();
        $data = DB::table('rooms')->select('room_type')->where('status', 'Ready')->distinct()->get();
        return view('formbooking.bookingedit', compact('bookingEdit', 'data'));
    }

    /**
     * Check for booking time conflicts
     */
     private function hasTimeConflict($room_type, $date, $time_start, $time_end, $excluding_bkg_id = null)
    {
        $query = Booking::where('room_type', $room_type)
            ->where('date', $date)
            ->where('status_meet', '!=', 'Finished')  // Only check conflicts for non-finished meetings
            ->where(function($q) use ($time_start, $time_end) {
                $q->where(function($query) use ($time_start, $time_end) {
                    $query->where('time_start', '<', $time_end)
                          ->where('time_end', '>', $time_start);
                });
            });

        if ($excluding_bkg_id) {
            $query->where('bkg_id', '!=', $excluding_bkg_id);
        }

        return $query->exists();
    }


    /** Save Record */
    public function saveRecord(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'room_type'     => 'required|string|max:255',
        'total_numbers' => 'required|string|max:255',
        'date' => 'required|date_format:Y-m-d',
        'time_start'    => 'required',
        'time_end'      => 'required|after:time_start',
        'email'         => 'required|email|max:255',
        'phone_number'  => 'required|string|max:255',
        'message'       => 'required|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        // Ubah tanggal dari request menjadi format Y-m-d menggunakan Carbon
        $formattedDate = Carbon::parse($request->date)->format('Y-m-d');
        $room = Room::where('room_type', $request->room_type)->first();
        if (!$room) {
            throw new \Exception('Room type not found.');
        }
        if ($request->total_numbers > $room->capacity) {
            flash()->error('Jumlah orang melebihi kapasitas ruangan.');
            return redirect()->back()->withInput();
        }
        // Check for time conflicts
        if ($this->hasTimeConflict(
            $request->room_type,
            $formattedDate,
            $request->time_start,
            $request->time_end
        )) {
            flash()->error('This room is already booked for the selected time slot.');
            return redirect()->back()->withInput();
        }

        $booking = new Booking;
        $booking->name = $request->name;
        $booking->room_type = $request->room_type;
        

        
        $booking->total_numbers = $request->total_numbers;
        $booking->date = $formattedDate; 
        $booking->time_start = $request->time_start;
        $booking->time_end = $request->time_end;
        $booking->email = $request->email;
        $booking->phone_number = $request->phone_number;
        $booking->message = $request->message;
        $booking->status_meet = 'Booked';
        $booking->save();

        DB::commit();
        flash()->success('Create new booking successfully :)');
        return redirect()->back();
    } catch (\Exception $e) {
        DB::rollback();
        flash()->error('Add Booking fail :)');
        Log::info($e->getMessage());
        return redirect()->back();
    }
}


    /** Update Record */
    public function updateRecord(Request $request)
{
    $request->validate([
        'bkg_id'        => 'required',
        'name'          => 'required|string|max:255',
        'room_type'     => 'required|string|max:255',
        'total_numbers' => 'required|integer|min:1',
        'date'          => 'required|date_format:Y-m-d',
        'time_start'    => 'required',
        'time_end'      => 'required|after:time_start',
        'email'         => 'required|email|max:255',
        'phone_number'  => 'required|string|max:255',
        'message'       => 'required|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        $booking = Booking::where('bkg_id', $request->bkg_id)->first();
        if (!$booking) {
            throw new \Exception('Booking not found.');
        }

        // Check kapasitas ruangan
        $room = Room::where('room_type', $request->room_type)->first();
        if (!$room) {
            throw new \Exception('Room type not found.');
        }

        if ($request->total_numbers > $room->capacity) {
            flash()->error('Jumlah orang melebihi kapasitas ruangan.');
            return redirect()->back()->withInput();
        }

        // Check for time conflicts
        if ($this->hasTimeConflict(
            $request->room_type,
            $request->date,
            $request->time_start,
            $request->time_end,
            $request->bkg_id
        )) {
            flash()->error('This room is already booked for the selected time slot.');
            return redirect()->back()->withInput();
        }

        $booking->name = $request->name;
        $booking->room_type = $request->room_type;
        $booking->total_numbers = $request->total_numbers;
        $booking->date = $request->date;
        $booking->time_start = $request->time_start;
        $booking->time_end = $request->time_end;
        $booking->email = $request->email;
        $booking->phone_number = $request->phone_number;
        $booking->message = $request->message;

        // Tetapkan status secara eksplisit
        if (Carbon::parse($request->date)->isFuture()) {
            $booking->status_meet = 'Booked';
        } 

        

        $booking->save();
        
        DB::commit();
        flash()->success('Booking updated successfully.');
        return redirect()->route('form/allbooking');
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Booking update error: ' . $e->getMessage());
        flash()->error('Failed to update booking: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
}

    /** Delete Record */
    public function deleteRecord(Request $request)
    {
        try {
            $booking = Booking::findOrFail($request->id);
            
            $booking->delete();
            
            flash()->success('Booking deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Booking delete fail :)');
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }

    /** View Calendar Page */
    public function calendar()
    {
        // Ambil semua room yang ready untuk filter
        $data = DB::table('rooms')
                ->where('status', 'Ready')
                ->get();
        
        return view('formbooking.calendar', compact('data'));
    }

    /** Get Events for Calendar */
    public function events(Request $request)
{
    try {
        // Update booking statuses first
        $this->updateBookingStatus();

        $start = $request->input('start');
        $end = $request->input('end');
        $room = $request->input('room');

        Log::info('Fetching events with:', [
            'start' => $start,
            'end' => $end,
            'room' => $room
        ]);

        $query = DB::table('bookings');

        if ($room) {
            $query->where('room_type', $room);
        }

        $bookings = $query->get();
        $events = [];

        foreach ($bookings as $booking) {
            $events[] = [
                'id' => $booking->bkg_id,
                'title' => $booking->room_type,
                'start' => $booking->date . 'T' . $booking->time_start,
                'end' => $booking->date . 'T' . $booking->time_end,
                'color' => $this->getEventColor($booking->room_type),
                'extendedProps' => [
                    'room_type' => $booking->room_type,
                    'name' => $booking->name,
                    'time_start' => $booking->time_start,
                    'time_end' => $booking->time_end,
                    'total_numbers' => $booking->total_numbers,
                    'message' => $booking->message,
                    'status_meet' => $booking->status_meet
                ]
            ];
        }

        Log::info('Returning events:', ['count' => count($events)]);
        return response()->json($events);

    } catch (\Exception $e) {
        Log::error('Error in events method:', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    /** Get color for different room types */
    private function getEventColor($roomType)
    {
        $colors = [
            'Meeting Room A' => '#007bff',
            'Meeting Room B' => '#28a745',
            'Conference Room' => '#dc3545',
            'Training Room' => '#ffc107',
            'Board Room' => '#6610f2',
            'Auditorium' => '#fd7e14'
        ];

        return $colors[$roomType] ?? '#6c757d';
    }

}
 