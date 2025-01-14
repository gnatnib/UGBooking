@extends('layouts.master')
@section('content')
    <?php
    $hour = date('G');
    $minute = date('i');
    $second = date('s');
    $msg = ' Today is ' . date('l, M d, Y.');
    
    if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59) {
        $greet = 'Good Morning,';
    } elseif ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59) {
        $greet = 'Good Day,';
    } elseif ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59) {
        $greet = 'Good Afternoon,';
    } elseif ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59) {
        $greet = 'Good Evening,';
    } else {
        $greet = 'Welcome,';
    }
    ?>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12 mt-5">
                        <h6>{{ $msg }}</h6>
                        <h3 class="page-title mt-3">{{ $greet }} {{ Auth::user()->name }}!</h3>
                    </div>
                </div>
            </div>

            <!-- First Row - Stats Cards -->
            <div class="row">
                <!-- Total Booking Card -->
                <div class="col-md-12 col-lg-6">
                    <div class="card board1 fill">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <div>
                                    <h3 class="card_widget_header">{{ $count }}</h3>
                                    <h6 class="text-muted">Total Bookings for {{ $currentMonthName }} </h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewbox="0 0 24 24" fill="none" stroke="#FFBF00" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                            </rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Booked Rooms Card -->
                <div class="col-md-12 col-lg-6">
                    <div class="card board1 fill">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <div>
                                    <h3 class="card_widget_header">{{ $totalTodayBookings }}</h3>
                                    <h6 class="text-muted">Today's Booked Meeting Rooms</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <button class="btn badge-view text-white" data-toggle="modal"
                                        data-target="#todayBookingsModal">
                                        <a href="#" class="btn btn-warning"
                                            style="background-color: #FFBF00; padding: 0.5rem 1rem; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border: none;">
                                            View
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row - Charts -->
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">ROOM BOOKINGS</h4>
                        </div>
                        <div class="card-body">
                            <div id="line-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Replace the existing chart card structure -->
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">ROOMS BOOKED</h4>
                        </div>
                        <div class="card-body">
                            <div id="donut-chart" class="donut-chart-container"></div>
                            <div class="chart-legend" id="chartLegend"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row - Booking Table -->
            <div class="row">
                <div class="col-md-12 d-flex">
                    <div class="card card-table flex-fill">
                        <!-- Bagian yang dimodifikasi -->
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-12 col-sm-12 col-md-4 mb-2 mb-md-0">
                                    <h4 class="card-title">Booking</h4>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                        @if (Auth::user()->role_name == 'admin' || Auth::user()->role_name == 'superadmin')
                                            <a href="{{ route('export.bookings') }}" class="btn btn-success mb-2 mb-sm-0">
                                                <i class="fas fa-download mr-2"></i> Export to CSV
                                            </a>
                                        @endif
                                        <div class="search-box">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search..."
                                                    id="searchBooking">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-search"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table table-hover table-center" id="bookingTable">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Name</th>
                                            <th>Division</th>
                                            <th>Email</th>
                                            <th>Participant Number</th>
                                            <th class="text-center">Room Type</th>
                                            <th class="text-right">Phone Number</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allBookings as $booking)
                                            <tr class="booking-row cursor-pointer" data-toggle="modal"
                                                data-booking-id="{{ $booking->bkg_id }}" data-name="{{ $booking->name }}"
                                                data-division="{{ $booking->user->division }}"
                                                data-email="{{ $booking->email }}"
                                                data-total-numbers="{{ $booking->total_numbers }}"
                                                data-room-type="{{ $booking->room_type }}"
                                                data-phone="{{ $booking->phone_number }}"
                                                data-status="{{ $booking->status_meet }}"
                                                data-date="{{ $booking->date }}"
                                                data-time-start="{{ $booking->time_start }}"
                                                data-time-end="{{ $booking->time_end }}">

                                                <td class="text-nowrap">
                                                    <div>{{ $booking->bkg_id }}</div>
                                                </td>
                                                <td class="text-nowrap">{{ $booking->name }}</td>
                                                <td class="text-nowrap">{{ $booking->user->division }}</td>
                                                <td><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></td>
                                                <td>{{ $booking->total_numbers }}</td>
                                                <td class="text-center">{{ $booking->room_type }}</td>
                                                <td class="text-center">
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone_number) }}"
                                                        target="_blank" class="whatsapp-link" title="Chat on WhatsApp">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 48 48" class="whatsapp-icon">
                                                            <path fill="#fff"
                                                                d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z">
                                                            </path>
                                                            <path fill="#cfd8dc"
                                                                d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5">
                                                            </path>
                                                            <path fill="#40c351"
                                                                d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z">
                                                            </path>
                                                            <path fill="#fff" fill-rule="evenodd"
                                                                d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"
                                                                clip-rule="evenodd">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    @if ($booking->status_meet == 'Booked')
                                                        <span class="badge badge-warning">Booked</span>
                                                    @elseif($booking->status_meet == 'In meeting')
                                                        <span class="badge badge-success">In Meeting</span>
                                                    @elseif ($booking->status_meet == 'Finished')
                                                        <span class="badge badge-green">Finished</span>
                                                    @elseif($booking->status_meet == 'cancel')
                                                        <span class="badge badge-danger">Cancel</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details Modal -->
            <div class="modal fade" id="bookingDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Booking Details</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="booking-info">
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Booking ID</div>
                                    <div class="col-7" id="modalBookingId"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Date</div>
                                    <div class="col-7" id="modalDate"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Time</div>
                                    <div class="col-7" id="modalTime"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Name</div>
                                    <div class="col-7" id="modalName"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Division</div>
                                    <div class="col-7" id="modalDivision"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Email</div>
                                    <div class="col-7" id="modalEmail"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Phone</div>
                                    <div class="col-7" id="modalPhone"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Room</div>
                                    <div class="col-7" id="modalRoom"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Participants</div>
                                    <div class="col-7" id="modalParticipants"></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-5 font-weight-bold">Status</div>
                                    <div class="col-7" id="modalStatus"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for Today's Bookings -->
            <div class="modal fade" id="todayBookingsModal" tabindex="-1" role="dialog"
                aria-labelledby="todayBookingsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="todayBookingsModalLabel">
                                <i class="fas fa-calendar-day mr-2"></i>Today's Meeting Schedule
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($todayBookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Room</th>
                                                <th>Booked By</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($todayBookings as $booking)
                                                <tr>
                                                    <td>
                                                        <span class="font-weight-bold">
                                                            {{ \Carbon\Carbon::parse($booking->time_start)->format('H:i') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($booking->time_end)->format('H:i') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="room-type">{{ $booking->room_type }}</span>
                                                    </td>
                                                    <td>
                                                        <div>{{ $booking->name }}</div>
                                                        <small class="text-muted">{{ $booking->division }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-pill badge-info">
                                                            {{ \Carbon\Carbon::parse($booking->time_start)->diffInHours($booking->time_end) }}
                                                            hours
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <h5 class="text-muted mt-3">No meetings scheduled for today</h5>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            @push('styles')
                <style>
                    /* Export Button Styling */
                    .btn-export {
                        background-color: #FFBF00;
                        border: none;
                        color: #000;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        transition: all 0.3s ease;
                        font-weight: 500;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .btn-export:hover {
                        background-color: #F1B100;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    }

                    .btn-export i {
                        font-size: 1rem;
                    }

                    /* Search Box Styling */
                    .input-group {
                        position: relative;
                        max-width: 300px;
                        margin: 0 !important;
                    }

                    .input-group .form-control {
                        border-radius: 0.5rem 0 0 0.5rem;
                        border: 1px solid #ddd;
                        padding: 0.5rem 1rem;
                    }

                    .input-group .input-group-append .input-group-text {
                        background-color: #fff;
                        border-radius: 0 0.5rem 0.5rem 0;
                        border: 1px solid #ddd;
                        border-left: none;
                    }

                    .input-group .form-control:focus {
                        border-color: #FFBF00;
                        box-shadow: 0 0 0 2px rgba(255, 191, 0, 0.2);
                        outline: none;
                    }

                    /* Responsive styles */
                    @media (max-width: 768px) {
                        .card-header {
                            flex-direction: column;
                            align-items: stretch !important;
                            gap: 1rem;
                        }

                        .d-flex.gap-3 {
                            flex-direction: column;
                        }

                        .btn-export {
                            width: 100%;
                            justify-content: center;
                        }

                        .input-group {
                            width: 100% !important;
                            max-width: 100%;
                            margin: 0 !important;
                        }
                    }

                    /* Chart Responsiveness */
                    .donut-chart-container {
                        min-height: 300px;
                        position: relative;
                        width: 100%;
                    }

                    @media (min-width: 768px) {
                        .donut-chart-container {
                            min-height: 400px;
                        }
                    }

                    /* Export Button Styling */
                    .btn-export {
                        background-color: #FFBF00;
                        border: none;
                        color: #000;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        transition: all 0.3s ease;
                        font-weight: 500;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .btn-export:hover {
                        background-color: #F1B100;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    }

                    .btn-export i {
                        font-size: 1rem;
                    }

                    /* Chart Legend Styling */
                    .chart-legend {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        gap: 1rem;
                        margin-top: 1rem;
                        padding: 0.5rem;
                    }

                    .legend-item {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        font-size: 0.875rem;
                        color: #666;
                    }

                    .legend-color {
                        width: 12px;
                        height: 12px;
                        border-radius: 50%;
                    }

                    /* Search Box Styling */
                    .search-container {
                        position: relative;
                        max-width: 300px;
                        width: 100%;
                        margin: 0.5rem 0;
                    }

                    .search-container input {
                        width: 100%;
                        padding: 0.5rem 1rem;
                        padding-right: 2.5rem;
                        border: 1px solid #ddd;
                        border-radius: 0.5rem;
                        transition: all 0.3s ease;
                    }

                    .search-container input:focus {
                        border-color: #FFBF00;
                        box-shadow: 0 0 0 2px rgba(255, 191, 0, 0.2);
                        outline: none;
                    }

                    .search-container i {
                        position: absolute;
                        right: 0.75rem;
                        top: 50%;
                        transform: translateY(-50%);
                        color: #666;
                    }

                    /* Add to your existing CSS */
                    @media (max-width: 768px) {
                        #donut-chart {
                            display: flex !important;
                            justify-content: center !important;
                            align-items: center !important;
                            margin: 0 auto !important;
                            padding: 0 !important;
                            width: 100% !important;
                        }

                        .apexcharts-canvas {
                            margin: 0 auto !important;
                            display: block !important;
                        }

                        .card-body {
                            padding: 1rem !important;
                        }

                        .apexcharts-legend {
                            padding: 0 !important;
                            justify-content: center !important;
                            left: 0 !important;
                            right: 0 !important;
                            margin: 0 auto !important;
                        }
                    }

                    /* General chart styles */
                    .card-chart {
                        background: #fff;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        margin-bottom: 1rem;
                    }

                    .donut-chart-container {
                        position: relative;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                        min-height: 300px;
                    }

                    .booking-row {
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }

                    .booking-row:hover {
                        background-color: #f8f9fa;
                    }

                    .booking-info .row {
                        border-bottom: 1px solid #eee;
                        padding: 8px 0;
                    }

                    .booking-info .row:last-child {
                        border-bottom: none;
                    }

                    #bookingDetailsModal .modal-header {
                        background-color: #FFBF00;
                        color: white;
                    }

                    #bookingDetailsModal .close {
                        color: white;
                    }

                    .booking-info {
                        padding: 15px;
                    }

                    /* Card & Button Styles */
                    .cursor-pointer {
                        cursor: pointer;
                    }

                    .btn-view {
                        background-color: white;
                        color: #FFBF00;
                        border: none;
                        padding: 8px 20px;
                        border-radius: 5px;
                        font-weight: 500;
                        transition: all 0.3s ease;
                    }

                    .btn-view:hover {
                        background-color: white;
                        color: #FFBF00;
                        transform: translateY(-2px);
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }

                    /* Modal Styles */
                    .modal-content {
                        border-radius: 15px;
                        overflow: hidden;
                        border: none;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                    }

                    .modal-header {
                        background-color: #FFBF00 !important;
                        border-bottom: none;
                        padding: 20px 30px;
                        position: relative;
                    }

                    .modal-header .modal-title {
                        color: white;
                        font-weight: 600;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }

                    .modal-header .close {
                        color: white;
                        opacity: 0.8;
                        text-shadow: none;
                        transition: all 0.3s ease;
                        padding: 1rem;
                        margin: -1rem;
                    }

                    .modal-header .close:hover {
                        opacity: 1;
                    }

                    .modal-body {
                        padding: 30px;
                    }

                    /* Table Styles */
                    .table {
                        margin-bottom: 0;
                    }

                    .table thead th {
                        background: #f8f9fa;
                        color: #2C3E50;
                        font-weight: 600;
                        border-bottom: 2px solid #FFBF00;
                        padding: 15px;
                    }

                    .table td {
                        padding: 15px;
                        vertical-align: middle;
                        border-top: 1px solid #f0f0f0;
                    }

                    .room-type {
                        background-color: #FFF3E0;
                        color: #FF9800;
                        padding: 5px 10px;
                        border-radius: 5px;
                        font-size: 0.9rem;
                        display: inline-block;
                    }

                    .btn.badge-view {
                        background-color: #FFBF00;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        border: none;
                    }

                    .btn.badge-view:hover {
                        background-color: #F1B100;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    }

                    .badge-info {
                        background-color: #FFBF00;
                        color: white;
                        padding: 5px 10px;
                        font-weight: 500;
                        border-radius: 5px;
                    }

                    .table tr {
                        transition: all 0.3s ease;
                    }

                    .table tbody tr:hover {
                        background-color: #FFF8E1;
                        transform: translateX(5px);
                    }

                    /* Text Styles */
                    .text-muted {
                        color: #666 !important;
                    }

                    small.text-muted {
                        font-size: 85%;
                    }

                    /* Empty State Styles */
                    .text-center.py-5 {
                        padding: 40px 0;
                    }

                    .text-center.py-5 img {
                        margin-bottom: 20px;
                        opacity: 0.6;
                        max-width: 150px;
                    }

                    .text-center.py-5 h5 {
                        color: #2C3E50;
                        margin-bottom: 10px;
                        font-weight: 500;
                    }

                    /* Card Hover Effect */
                    .board1.fill:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        transition: all 0.3s ease;
                    }

                    /* Table Responsiveness */
                    .table-responsive {
                        overflow-x: auto;
                        -webkit-overflow-scrolling: touch;
                    }

                    /* Modal Footer */
                    .modal-footer {
                        border-top: 1px solid #f0f0f0;
                        padding: 15px 30px;
                    }

                    .btn-secondary {
                        background-color: #f8f9fa;
                        color: #2C3E50;
                        border: 1px solid #dee2e6;
                        transition: all 0.3s ease;
                    }

                    .btn-secondary:hover {
                        background-color: #e9ecef;
                        border-color: #dee2e6;
                        transform: translateY(-1px);
                    }

                    /* Card Header Base Styles */
                    .card-header {
                        padding: 1.5rem;
                        background: #fff;
                    }

                    /* Gap Utility */
                    .gap-2 {
                        gap: 0.5rem;
                    }

                    /* Export Button Styles */
                    .btn-export {
                        background-color: #FFBF00;
                        border: none;
                        color: #000;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        transition: all 0.3s ease;
                        font-weight: 500;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        white-space: nowrap;
                        min-width: 140px;
                        justify-content: center;
                    }

                    .btn-export:hover {
                        background-color: #F1B100;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        color: #000;
                        text-decoration: none;
                    }

                    .btn-export i {
                        font-size: 1rem;
                    }

                    /* Search Box Container */
                    .search-box {
                        flex: 1;
                        max-width: 300px;
                        min-width: 200px;
                    }

                    /* Search Input Group */
                    .search-box .input-group {
                        width: 100% !important;
                    }

                    .search-box .form-control {
                        height: 38px;
                        border-radius: 0.5rem 0 0 0.5rem;
                        border: 1px solid #ddd;
                        padding: 0.5rem 1rem;
                    }

                    .search-box .input-group-append .input-group-text {
                        background-color: #fff;
                        border-radius: 0 0.5rem 0.5rem 0;
                        border: 1px solid #ddd;
                        border-left: none;
                    }

                    .search-box .form-control:focus {
                        border-color: #FFBF00;
                        box-shadow: 0 0 0 2px rgba(255, 191, 0, 0.2);
                        outline: none;
                    }

                    /* Large Desktop */
                    @media (min-width: 1200px) {
                        .search-box {
                            max-width: 300px;
                        }
                    }

                    /* Desktop */
                    @media (min-width: 992px) and (max-width: 1199px) {
                        .search-box {
                            max-width: 250px;
                        }
                    }

                    /* Tablet */
                    @media (min-width: 768px) and (max-width: 991px) {
                        .card-header {
                            padding: 1.25rem;
                        }

                        .search-box {
                            max-width: 220px;
                        }
                    }

                    /* Small Tablet */
                    @media (min-width: 576px) and (max-width: 767px) {
                        .card-header {
                            padding: 1rem;
                        }

                        .btn-export {
                            min-width: 120px;
                        }

                        .search-box {
                            max-width: 200px;
                        }
                    }

                    /* Mobile */
                    @media (max-width: 575px) {
                        .card-header {
                            padding: 1rem;
                        }

                        .d-flex.flex-column.flex-sm-row {
                            gap: 0.75rem;
                        }

                        .btn-export {
                            width: 100%;
                            min-width: 100%;
                        }

                        .search-box {
                            max-width: 100%;
                            width: 100%;
                        }

                        .search-box .input-group {
                            width: 100% !important;
                        }
                    }

                    /* Extra Small Mobile */
                    @media (max-width: 375px) {
                        .card-header {
                            padding: 0.75rem;
                        }

                        .btn-export {
                            padding: 0.4rem 0.75rem;
                        }
                    }

                    .whatsapp-link {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        transition: transform 0.2s ease;
                        padding: 0.5rem;
                    }

                    .whatsapp-link:hover {
                        transform: scale(1.1);
                    }

                    .whatsapp-icon {
                        vertical-align: middle;
                    }

                    td.text-center {
                        text-align: center;
                    }
                </style>
            @endpush

            @push('scripts')
                <script>
                    var roomStatsJson = {!! $roomStatsJson !!};
                    $(document).ready(function() {

                        // bar chart dashboard
                        window.barChart = Morris.Bar({
                            element: 'line-chart',
                            data: roomStatsJson,
                            xkey: 'y',
                            ykeys: ['a'],
                            labels: ['Total Bookings'],
                            barColors: ['#FFBF00'],
                            hideHover: 'auto',
                            gridLineColor: '#eef0f2',
                            resize: true,
                            barSizeRatio: 0.42,
                            xLabelMargin: 10,
                            xLabelAngle: window.innerWidth <= 768 ? 20 : 0,
                            gridTextSize: window.innerWidth <= 768 ? 8 : 10,
                            ymin: 0,
                            parseTime: false,
                            postUnits: '', // Add this line
                            goals: [0],
                            goalLineColors: ['#eef0f2'],
                            yLabelFormat: function(y) {
                                return Math.round(y);
                            },
                            numLines: Math.ceil(Math.max(...roomStatsJson.map(item => item.a))) + 1,
                            grid: true,
                            gridSteps: 1,
                            padding: window.innerWidth <= 768 ? 30 : 20,
                            labelMargin: window.innerWidth <= 768 ? 8 : 4,
                            redraw: true
                        });

                        // Donut chart
                        var divisionData = {!! $divisionStatsJson !!};
                        var options = {
                            chart: {
                                type: 'donut',
                                height: 350,
                                background: 'transparent',
                                events: {
                                    mounted: function(chartContext, config) {
                                        const chart = document.querySelector('#donut-chart');
                                        if (chart) {
                                            chart.style.display = 'flex';
                                            chart.style.justifyContent = 'center';
                                        }
                                    }
                                }
                            },
                            series: divisionData.map(item => item.value),
                            labels: divisionData.map(item => item.name),
                            colors: ['#1D5B79', '#FAD02E', '#2E97A7'],
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '70%',
                                        labels: {
                                            show: false // Ini akan menghilangkan label di tengah donut
                                        }
                                    },
                                    offsetY: 0
                                }
                            },
                            dataLabels: {
                                enabled: true, // Mempertahankan label persentase di setiap bagian
                                formatter: function(val) {
                                    return val.toFixed(1) + '%';
                                }
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center',
                                fontSize: '14px',
                                markers: {
                                    width: 12,
                                    height: 12,
                                    radius: 6
                                },
                                itemMargin: {
                                    horizontal: 10,
                                    vertical: 5
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        height: 300
                                    },
                                    legend: {
                                        position: 'bottom',
                                        fontSize: '12px',
                                        offsetY: 0
                                    }
                                }
                            }],
                            tooltip: {
                                y: {
                                    formatter: function(value) {
                                        return value;
                                    }
                                }
                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#donut-chart"), options);
                        chart.render();

                        let resizeTimeout;
                        window.addEventListener('resize', function() {
                            clearTimeout(resizeTimeout);
                            resizeTimeout = setTimeout(function() {
                                if (window.barChart) {
                                    barChart.options.xLabelAngle = window.innerWidth <= 768 ? 20 : 0;
                                    barChart.options.gridTextSize = window.innerWidth <= 768 ? 8 : 10;
                                    barChart.options.padding = window.innerWidth <= 768 ? 30 : 20;
                                    barChart.options.labelMargin = window.innerWidth <= 768 ? 8 : 4;
                                    barChart.redraw(); // Force redraw
                                }
                            }, 250);
                        });

                        setTimeout(() => {
                            if (window.barChart) {
                                barChart.redraw();
                            }
                        }, 100);

                        // Make sure chart is centered on mobile
                        if (window.innerWidth <= 768) {
                            chart.updateOptions({
                                chart: {
                                    width: '100%',
                                    height: 300
                                },
                                legend: {
                                    position: 'bottom',
                                    horizontalAlign: 'center',
                                    floating: false,
                                    offsetY: 10
                                },
                                plotOptions: {
                                    pie: {
                                        offsetX: 0,
                                        offsetY: -10
                                    }
                                }
                            });
                        }

                        // Search functionality
                        $("#searchBooking").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            $("#bookingTable tbody tr").filter(function() {
                                var text = $(this).text().toLowerCase();
                                $(this).toggle(text.indexOf(value) > -1);
                            });
                        });

                        // Refresh modal content every minute
                        setInterval(function() {
                            if ($('#todayBookingsModal').is(':visible')) {
                                location.reload();
                            }
                        }, 60000);
                    });

                    // Add this inside your existing $(document).ready function
                    $('#bookingTable tbody tr').on('click', function(e) {
                        // Prevent triggering when clicking on WhatsApp icon
                        if ($(e.target).closest('.whatsapp-link').length) {
                            return;
                        }

                        const bookingId = $(this).data('booking-id');
                        const name = $(this).data('name');
                        const division = $(this).data('division');
                        const email = $(this).data('email');
                        const totalNumbers = $(this).data('total-numbers');
                        const roomType = $(this).data('room-type');
                        const phone = $(this).data('phone');
                        const status = $(this).data('status');
                        const date = $(this).data('date');
                        const timeStart = $(this).data('time-start');
                        const timeEnd = $(this).data('time-end');

                        // Format status badge
                        let statusBadge;
                        switch (status) {
                            case 'Booked':
                                statusBadge = '<span class="badge badge-warning">Booked</span>';
                                break;
                            case 'In meeting':
                                statusBadge = '<span class="badge badge-success">In Meeting</span>';
                                break;
                            case 'Finished':
                                statusBadge = '<span class="badge badge-green">Finished</span>';
                                break;
                            case 'cancel':
                                statusBadge = '<span class="badge badge-danger">Cancel</span>';
                                break;
                            default:
                                statusBadge = status;
                        }

                        // Update modal content
                        $('#modalBookingId').text(bookingId);
                        $('#modalDate').text(date);
                        $('#modalTime').text(`${timeStart} - ${timeEnd}`);
                        $('#modalName').text(name);
                        $('#modalDivision').text(division);
                        $('#modalEmail').html(`<a href="mailto:${email}">${email}</a>`);
                        $('#modalPhone').text(phone);
                        $('#modalRoom').text(roomType);
                        $('#modalParticipants').text(totalNumbers);
                        $('#modalStatus').html(statusBadge);

                        // Show modal
                        $('#bookingDetailsModal').modal('show');
                    });
                </script>
            @endpush

        @endsection
