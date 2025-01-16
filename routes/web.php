<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BookingExportController;
use App\Http\Controllers\view;
use App\Http\Middleware\RoleMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('home');
    });
    Route::get('home', function () {
        return view('home');
    });
});

Auth::routes();

// ----------------------------- main dashboard ------------------------------//
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::get('/profile', 'profile')->name('profile');
    Route::post('/update-password', 'updatePassword')->name('update.password');
    Route::post('/profile/update', 'updateProfile')->name('profile.update');
});

// -----------------------------login----------------------------------------//
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

// ------------------------------ register ---------------------------------//
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'storeUser')->name('register');
});

// ----------------------------- forget password ----------------------------//
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forget-password', 'getEmail')->name('forget-password');
    Route::post('forget-password', 'postEmail')->name('forget-password');
});


// ----------------------------- booking -----------------------------//
Route::controller(BookingController::class)->group(function () {
    Route::get('form/allbooking', 'allbooking')->name('form/allbooking')->middleware('auth');
    Route::get('form/booking/edit/{bkg_id}', 'bookingEdit')->middleware('auth');
    Route::get('form/booking/add', 'bookingAdd')->middleware('auth')->name('form/booking/add');
    Route::post('form/booking/save', 'saveRecord')->middleware('auth')->name('form/booking/save');
    Route::post('form/booking/update', 'updateRecord')->middleware('auth')->name('form/booking/update');
    Route::post('form/booking/delete', 'deleteRecord')->middleware('auth')->name('form/booking/delete');
    Route::post('form/booking/endMeeting', 'endMeeting')->middleware('auth')->name('form/booking/endMeeting'); // Route untuk end meeting
    Route::post('form/booking/cancel', 'cancelRecord')->middleware('auth')->name('form.booking.cancel');

    // Calendar routes
    Route::get('form/booking/calendar', 'calendar')->middleware('auth')->name('form/booking/calendar');
    Route::get('form/booking/events', 'events')->middleware('auth')->name('form/booking/events');
});
// ----------------------------- rooms -----------------------------//
Route::controller(RoomsController::class)
    ->middleware(['auth', RoleMiddleware::class]) 
    ->group(function () {
        Route::get('form/allrooms/page', 'allrooms')->name('form/allrooms/page');
        Route::get('form/addroom/page', 'addRoom')->name('form/addroom/page');
        Route::get('form/room/edit/{bkg_room_id}', 'editRoom');
        Route::post('form/room/save', 'saveRecordRoom')->name('form/room/save');
        Route::post('form/room/delete', 'deleteRecord')->name('form/room/delete');
        Route::post('form/room/update', 'updateRecord')->name('form/room/update');
        // Add ruangan
        Route::post('form/room-type/add', 'addRoomType')->name('room.type.add');
        // Delete ruangan
        Route::post('form/room-type/delete', 'deleteRoomType')->name('room.type.delete');
        Route::get('/api/room-details/{roomType}', 'getRoomDetails');
    });


// ----------------------- user management -------------------------//
Route::controller(UserManagementController::class)->middleware(['auth', RoleMiddleware::class])->group(function () {
    Route::get('users/list/page', 'userList')->name('users/list/page');
    Route::get('users/add/new', 'userAddNew')->name('users/add/new');
    /** add new users */
    Route::get('users/add/edit/{user_id}', 'userView');
    /** update record */
    Route::post('users/update', 'userUpdate')->name('users/update');
    /** delete record */
    Route::get('users/delete/{id}', 'userDelete')->name('users/delete');
    /** get all data users */
    Route::get('get-users-data', 'getUsersData')->name('get-users-data');
    Route::get('user/list', 'userList')->name('user/list');
    Route::get('user/add/new', 'userAddNew')->name('user/add/new');
    Route::post('user/save', 'saveUser')->name('user/save');
});


// ----------------------- booking management -------------------------//
Route::controller(BookingExportController::class)->group(function () {
    Route::get('export/bookings', 'export')->middleware('auth')->name('export.bookings');
});

// Public routes (no authentication required)
Route::controller(view::class)->group(function () {
    Route::get('/publiccalendar', 'publicCalendar')->name('publiccalendar');
    Route::get('/publiccalendar/events', 'publicEvents')->name('publiccalendar.events');
});
