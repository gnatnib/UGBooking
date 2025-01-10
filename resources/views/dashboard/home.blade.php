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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" 
                                            stroke="#FFBF00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                            class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
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
                                    <button class="btn btn-view" data-toggle="modal" data-target="#todayBookingsModal">
                                        View
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
                            <h4 class="card-title">VISITORS</h4>
                        </div>
                        <div class="card-body">
                            <div id="line-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">ROOMS BOOKED</h4>
                        </div>
                        <div class="card-body">
                            <div id="donut-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row - Booking Table -->
            <div class="row">
                <div class="col-md-12 d-flex">
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h4 class="card-title float-left mt-2">Booking</h4>
                            <div class="float-right">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search..." id="searchBooking">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
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
                                            <th>Email</th>
                                            <th>Participant Number</th>
                                            <th class="text-center">Room Type</th>
                                            <th class="text-right">Phone Number</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allBookings as $booking)
                                            <tr>
                                                <td class="text-nowrap">
                                                    <div>{{ $booking->bkg_id }}</div>
                                                </td>
                                                <td class="text-nowrap">{{ $booking->name }}</td>
                                                <td><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></td>
                                                <td>{{ $booking->total_numbers }}</td>
                                                <td class="text-center">{{ $booking->room_type }}</td>
                                                <td class="text-right">
                                                    <div>{{ $booking->phone_number }}</div>
                                                </td>
                                                <td class="text-center">
                                                    @if($booking->status_meet == 'Booked')
                                                        <span class="badge badge-warning">Booked</span>
                                                    @elseif($booking->status_meet == 'In meeting')
                                                        <span class="badge badge-danger">In Meeting</span>
                                                    @else
                                                        <span class="badge badge-succes">Finished</span>
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
        </div>
    </div>

    <!-- Modal for Today's Bookings -->
    <div class="modal fade" id="todayBookingsModal" tabindex="-1" role="dialog" aria-labelledby="todayBookingsModalLabel" aria-hidden="true">
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
                    @if($todayBookings->count() > 0)
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
                                    @foreach($todayBookings as $booking)
                                        <tr>
                                            <td>
                                                <span class="font-weight-bold">
                                                    {{ \Carbon\Carbon::parse($booking->time_start)->format('H:i') }} - 
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
                                                    {{ \Carbon\Carbon::parse($booking->time_start)->diffInHours($booking->time_end) }} hours
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="/assets/img/no-meetings.svg" alt="No Meetings" style="width: 150px; opacity: 0.5;">
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
/* Card & Button Styles */
.cursor-pointer {
    cursor: pointer;
}

.btn-view {
    background-color: #FFBF00;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background-color: #FFB300;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Modal Styles */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Existing search functionality
    $("#searchBooking").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#bookingTable tbody tr").filter(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(value) > -1);
        });
    });

    // Add this code for the donut chart
    var divisionData = {!! $divisionStatsJson !!};
    
    var options = {
        chart: {
            type: 'donut',
            height: 350
        },
        series: divisionData.map(item => item.value),
        labels: divisionData.map(item => item.name),
        colors: ['#00A36C', '#2E8B57', '#3CB371', '#66CDAA', '#8FBC8F', '#90EE90', '#98FB98'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        title: {
            text: 'Room Bookings by Division',
            align: 'center'
        },
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#donut-chart"), options);
    chart.render();

    // Refresh modal content every minute
    setInterval(function() {
        if ($('#todayBookingsModal').is(':visible')) {
            location.reload();
        }
    }, 60000);
});
</script>

@endpush

@endsection