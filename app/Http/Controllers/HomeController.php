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
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $user = Auth::user();
        $today = Carbon::today();

        // Get room booking statistics for bar chart
        $roomBookings = DB::table('bookings')
            ->select('room_type', DB::raw('count(*) as total'))
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->groupBy('room_type')
            ->get();

        // Format data for Morris Bar chart
        $roomStats = [];
        foreach ($roomBookings as $booking) {
            $roomStats[] = [
                'y' => $booking->room_type,
                'a' => $booking->total
            ];
        }
        $roomStatsJson = json_encode($roomStats);

        // Month names in Indonesian
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $currentMonthName = $monthNames[$currentMonth];

        // Get division statistics for donut chart
        $divisionBookings = DB::table('bookings')
            ->join('users', 'bookings.name', '=', 'users.name')
            ->select('users.division', DB::raw('count(*) as total'))
            ->whereMonth('bookings.date', $currentMonth)
            ->whereYear('bookings.date', $currentYear)
            ->groupBy('users.division')
            ->get();

        // Format the data for the donut chart
        $divisionStats = [];
        foreach ($divisionBookings as $booking) {
            $divisionStats[] = [
                'name' => $booking->division,
                'value' => $booking->total
            ];
        }

        // Get total bookings for current month
        $count = Booking::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();

        // Di HomeController, di method index()
        if ($user->role_name === 'admin' || $user->role_name === 'superadmin') {
            $allBookings = Booking::with('user')
                ->orderBy('date', 'desc')
                ->orderBy('time_start', 'desc')
                ->get();
        } else {
            $allBookings = Booking::with('user')
                ->where('name', $user->name)
                ->orderBy('date', 'desc')
                ->orderBy('time_start', 'desc')
                ->get();
        }

        $divisionStatsJson = json_encode($divisionStats);

        // Get today's bookings
        $todayBookings = Booking::whereDate('date', $today)
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->get();

        $totalTodayBookings = Booking::whereDate('date', $today)->count();

        return view('dashboard.home', compact(
            'allBookings',
            'count',
            'currentMonthName',
            'todayBookings',
            'totalTodayBookings',
            'divisionStatsJson',
            'roomStatsJson'
        ));
    }

    /**
     * Show the user profile page
     */
    public function profile()
    {
        return view('profile');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        // Validate request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user = User::find($user->id);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully');
    }

    /**
     * Update user profile
     */
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
