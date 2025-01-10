<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingExportController extends Controller
{
    public function export()
    {
        if (!in_array(Auth::user()->role_name, ['admin', 'superadmin'])) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $currentMonth = Carbon::now()->format('F Y');
        $filename = "booking_report_{$currentMonth}.csv";

        // Get bookings data dengan join berdasarkan nama
        $bookings = Booking::select(
            'bookings.bkg_id',
            'bookings.name',
            'bookings.phone_number',
            'bookings.time_start',
            'bookings.time_end',
            'bookings.room_type',
            'bookings.status_meet',
            'bookings.total_numbers',
            'users.division'
        )
        ->leftJoin('users', 'bookings.name', '=', 'users.name')  // Join berdasarkan nama
        ->orderBy('bookings.time_start', 'desc')
        ->get();

        // Get division summary juga menggunakan nama
        $divisionSummary = DB::table('bookings')
            ->join('users', 'bookings.name', '=', 'users.name')
            ->select('users.division', DB::raw('COUNT(*) as total_bookings'))
            ->whereYear('bookings.created_at', Carbon::now()->year)
            ->whereMonth('bookings.created_at', Carbon::now()->month)
            ->groupBy('users.division')
            ->get();

        // Room type summary tetap sama
        $roomTypeSummary = DB::table('bookings')
            ->select('room_type', DB::raw('COUNT(*) as total_bookings'))
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('room_type')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($bookings, $divisionSummary, $roomTypeSummary) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Booking Report - ' . Carbon::now()->format('F Y')]);
            fputcsv($file, []);
            
            // Booking Details
            fputcsv($file, ['BOOKING DETAILS']);
            fputcsv($file, ['Booking ID', 'Name', 'Division', 'Phone Number', 'Start Time', 'End Time', 'Room Type', 'Status', 'Participant Number']);
            
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->bkg_id,
                    $booking->name,
                    $booking->division ?? 'N/A',
                    $booking->phone_number,
                    Carbon::parse($booking->time_start)->format('Y-m-d H:i'),
                    Carbon::parse($booking->time_end)->format('Y-m-d H:i'),
                    $booking->room_type,
                    $booking->status_meet,
                    $booking->total_numbers
                ]);
            }
            
            fputcsv($file, []);
            fputcsv($file, []);
            
            // Division Summary
            fputcsv($file, ['DIVISION SUMMARY - ' . Carbon::now()->format('F Y')]);
            fputcsv($file, ['Division', 'Total Bookings']);
            
            foreach ($divisionSummary as $summary) {
                fputcsv($file, [
                    $summary->division,
                    $summary->total_bookings
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, []);
            
            // Room Type Summary
            fputcsv($file, ['ROOM TYPE SUMMARY - ' . Carbon::now()->format('F Y')]);
            fputcsv($file, ['Room Type', 'Total Bookings']);
            
            foreach ($roomTypeSummary as $summary) {
                fputcsv($file, [
                    $summary->room_type,
                    $summary->total_bookings
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}