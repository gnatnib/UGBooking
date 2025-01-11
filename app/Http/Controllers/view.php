<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class view extends Controller
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

   public function publicCalendar()
{
    $this->updateBookingStatus();
    
    $data = DB::table('rooms')
            ->where('status', 'Ready')
            ->get();
    
    return view('formbooking.publiccalendar', compact('data'));
}

    public function publicEvents(Request $request)
    {
        try {
            // Update booking statuses first
            $this->updateBookingStatus();

            $start = $request->input('start');
            $end = $request->input('end');
            $room = $request->input('room');

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

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error in public events method:', ['error' => $e->getMessage()]);
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