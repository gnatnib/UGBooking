<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class BookingController extends Controller
{
    /** View Page All */
    public function allbooking()
    {

        $allBookings = DB::table('bookings')->get();
        return view('formbooking.allbooking', compact('allBookings'));
    }

    /** Page */
    public function bookingAdd()
    {
        $data = DB::table('rooms')->select('room_type')->where('status', 'Ready')->distinct()->get();
        $user = DB::table('users')->get();
        return view('formbooking.bookingadd', compact('data', 'user'));
    }

    /** View Record */
    public function bookingEdit($bkg_id)
    {
        $bookingEdit = DB::table('bookings')->where('bkg_id', $bkg_id)->first();
        return view('formbooking.bookingedit', compact('bookingEdit'));
    }

    /**
     * Check for booking time conflicts
     */
    private function hasTimeConflict($room_type, $date, $time_start, $time_end, $excluding_bkg_id = null)
    {
        $query = Booking::where('room_type', $room_type)
            ->where('date', $date)
            ->where(function($q) use ($time_start, $time_end) {
                // Check if the new booking overlaps with any existing booking
                $q->where(function($query) use ($time_start, $time_end) {
                    $query->where('time_start', '<', $time_end)
                          ->where('time_end', '>', $time_start);
                });
            });

        // Exclude current booking when updating
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
            'date'          => 'required|date',
            'time_start'    => 'required',
            'time_end'      => 'required|after:time_start',
            'email'         => 'required|email|max:255',
            'phone_number'  => 'required|string|max:255',
            'message'       => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Check for time conflicts
            if ($this->hasTimeConflict(
                $request->room_type,
                $request->date,
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
            $booking->date = $request->date;
            $booking->time_start = $request->time_start;
            $booking->time_end = $request->time_end;
            $booking->email = $request->email;
            $booking->phone_number = $request->phone_number;
            
            $booking->message = $request->message;
            $booking->status_meet = 'pending';
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
            'name'          => 'required|string|max:255',
            'room_type'     => 'required|string|max:255',
            'total_numbers' => 'required|string|max:255',
            'date'          => 'required|date',
            'time_start'    => 'required',
            'time_end'      => 'required|after:time_start',
            'email'         => 'required|email|max:255',
            'phone_number'  => 'required|string|max:255',
            
            'message'       => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Check for time conflicts excluding current booking
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

            

            $update = [
                'name'          => $request->name,
                'room_type'     => $request->room_type,
                'total_numbers' => $request->total_numbers,
                'date'          => $request->date,
                'time_start'    => $request->time_start,
                'time_end'      => $request->time_end,
                'email'         => $request->email,
                'phone_number'     => $request->phone_number,
                'message'       => $request->message,
            ];

            Booking::where('bkg_id', $request->bkg_id)->update($update);
        
            DB::commit();
            flash()->success('Updated booking successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Update booking fail :)');
            Log::info($e->getMessage());
            return redirect()->back();
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

}