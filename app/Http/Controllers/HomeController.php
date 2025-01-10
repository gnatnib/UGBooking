<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // home page
    

    public function index()
    {
        // Dapatkan bulan dan tahun saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $today = Carbon::today();
    
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentMonthName = $monthNames[$currentMonth];
    
        // Get today's bookings
        $todayBookings = Booking::whereDate('date', $today)
            ->orderBy('date', 'asc')  // Changed from start_time to date
            ->take(5)
            ->get();
        
        $totalTodayBookings = Booking::whereDate('date', $today)->count();
    
        // Ambil semua booking
        $allBookings = DB::table('bookings')->get();
    
        // Hitung jumlah booking untuk bulan ini
        $count = Booking::whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->count();
    
        return view('dashboard.home', compact('allBookings', 'count', 'currentMonthName', 'todayBookings', 'totalTodayBookings'));
    }

    // profile
    public function profile()
    {
        return view('profile');
    }

    public function updatePassword(Request $request)
    {
        // Validate request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user(); // instance of \Illuminate\Contracts\Auth\Authenticatable
        $user = User::find($user->id); // cast to User model

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:15',
            'division' => 'required|string|in:Building Management,Construction and Property,IT Business and Solution,Finance and Accounting,Human Capital and General Affair,Risk, System, and Compliance,Internal Audit',
            'department' => 'required|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->division = $request->division;
        $user->department = $request->department;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
