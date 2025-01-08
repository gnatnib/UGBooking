<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        $allBookings = DB::table('bookings')->get();
        return view('dashboard.home', compact('allBookings'));
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
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:15',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        // Use the User model to find and update
        $user = User::find(Auth::id());

        // Make sure to add the User model at the top of your controller:
        // use App\Models\User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->position = $request->position;
        $user->department = $request->department;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
