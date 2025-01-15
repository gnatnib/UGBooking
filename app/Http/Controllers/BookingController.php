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

            // Update bookings to 'Booked' if meeting has not started yet and status is not 'cancel'
            Booking::where('date', $currentDate)
                ->where('time_start', '>', $currentTime)
                ->where('status_meet', '!=', 'Booked')
                ->where('status_meet', '!=', 'cancel')
                ->update(['status_meet' => 'Booked']);

            // Update bookings that are currently in progress to 'In meeting' and status is not 'cancel'
            Booking::where('date', $currentDate)
                ->where('time_start', '<=', $currentTime)
                ->where('time_end', '>', $currentTime)
                ->where('status_meet', '!=', 'In meeting')
                ->where('status_meet', '!=', 'cancel')
                ->update(['status_meet' => 'In meeting']);

            // Update bookings that have ended to 'Finished' and status is not 'cancel'
            Booking::where(function ($query) use ($currentDate, $currentTime) {
                $query->where('date', '<', $currentDate)
                    ->orWhere(function ($q) use ($currentDate, $currentTime) {
                        $q->where('date', '=', $currentDate)
                            ->where('time_end', '<=', $currentTime);
                    });
            })
                ->where('status_meet', '!=', 'Finished')
                ->where('status_meet', '!=', 'cancel')
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
    public function allbooking(Request $request)
    {
        // Update booking statuses
        $this->updateBookingStatus();

        $user = Auth::user();
        $query = DB::table('bookings')
            ->leftJoin('users', 'bookings.name', '=', 'users.name')
            ->select('bookings.*', 'users.division');

        // Apply month filter if provided
        if ($request->filled('month')) {
            $query->whereMonth('bookings.date', $request->month);
        }

        // Apply year filter if provided
        if ($request->filled('year')) {
            $query->whereYear('bookings.date', $request->year);
        }

        // Apply user role based filtering
        if (!in_array($user->role_name, ['admin', 'superadmin'])) {
            $query->where('bookings.name', $user->name);
        }

        $allBookings = $query->orderBy('bookings.date', 'desc')
            ->orderBy('bookings.time_start', 'desc')
            ->get();

        // Generate years for dropdown (from 2020 to current year + 1)
        $years = range(2022, date('Y') + 1);

        // Months for dropdown
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        return view('formbooking.allbooking', compact('allBookings', 'months', 'years'));
    }

    /** Page */
    public function bookingAdd()
    {
        $currentUser = Auth::user();

        // If user is admin or superadmin, show all users
        if ($currentUser->role_name === 'admin' || $currentUser->role_name === 'superadmin') {
            $users = DB::table('users')->get();  // Changed variable name from $user to $users
        } else {
            // If regular user, only show their own data
            $users = DB::table('users')          // Changed variable name from $user to $users
                ->where('name', $currentUser->name)
                ->get();
        }

        $data = DB::table('rooms')->select('room_type')->where('status', 'Ready')->distinct()->get();

        return view('formbooking.bookingadd', compact('data', 'users')); // Changed 'user' to 'users' in compact
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
            ->whereNotIn('status_meet', ['Finished', 'cancel']) // Abaikan status 'Finished' dan 'cancel'
            ->where(function ($q) use ($time_start, $time_end) {
                $q->whereBetween('time_start', [$time_start, $time_end])
                    ->orWhereBetween('time_end', [$time_start, $time_end])
                    ->orWhereRaw('? BETWEEN time_start AND time_end', [$time_start])
                    ->orWhereRaw('? BETWEEN time_start AND time_end', [$time_end]);
            });

        if ($excluding_bkg_id) {
            $query->where('bkg_id', '!=', $excluding_bkg_id); // Abaikan booking yang sedang diperbarui
        }

        return $query->exists();
    }



    /** Save Record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'room_type'     => 'required|string|max:255',
            'total_numbers' => 'required|integer|min:1',
            'date'          => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today'
            ],
            'time_start'    => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $currentDateTime = Carbon::now();
                    $selectedDateTime = Carbon::parse($request->date . ' ' . $value);

                    if ($selectedDateTime->isPast()) {
                        $fail('Please select a time after the current time.');
                    }
                }
            ],
            'time_end'      => 'required|after:time_start',
            'email'         => 'required|email|max:255',
            'phone_number'  => 'required|string|max:255',
            'message'       => 'required|string|max:255',
        ], [
            'date.after_or_equal' => 'Please select today\'s date or a future date.',
            'time_end.after' => 'End time must be after start time.',
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
            'date'          => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time_start'    => ['required', 'date_format:H:i', function ($attribute, $value, $fail) use ($request) {
                $currentDateTime = Carbon::now();
                $selectedDateTime = Carbon::parse($request->date . ' ' . $value);

                if ($selectedDateTime->isPast()) {
                    $fail('The selected time must be greater than or equal to the current time.');
                }
            }],
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

    public function cancelRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            $validated = $request->validate([
                'id' => 'required|exists:bookings,bkg_id', // Pastikan ID valid dan ada di tabel
            ]);

            // Cari booking berdasarkan ID
            $booking = Booking::where('bkg_id', $validated['id'])->firstOrFail();

            // Ubah status_meet menjadi 'cancel'
            $booking->status_meet = 'cancel';
            $booking->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Booking successfully canceled',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel booking error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking',
            ], 500);
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

            // Filter by room if provided
            if ($room) {
                $query->where('room_type', $room);
            }

            // Add filter to exclude 'cancel' bookings
            $query->where('status_meet', '!=', 'cancel');

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
