@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title mt-5">Room Booking Calendar</h3>
                </div>
                <div class="col-auto">
                    <a href="{{ route('form/booking/add') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Room Filter -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Filter by Room</label>
                    <select class="form-control" id="roomFilter">
                        <option value="">All Rooms</option>
                        @foreach($data as $room)
                            <option value="{{ $room->room_type }}">{{ $room->room_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<!-- Add these in your master layout if not already present -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var selectedRoom = '';

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '18:00:00',
        allDaySlot: false,
        events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: '{{ route("form/booking/events") }}',
                type: 'GET',
                data: {
                    room: selectedRoom,
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr
                },
                success: function(response) {
                    successCallback(response);
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load booking data'
                    });
                    failureCallback(error);
                }
            });
        },
        eventClick: function(info) {
            showBookingDetails(info.event);
        },
        eventContent: function(arg) {
    // Tambahkan hanya elemen tambahan tanpa mengubah warna
    let statusBadge = '';
    if (arg.event.extendedProps.status_meet === 'pending') {
        statusBadge = '<span class="badge badge-warning">Pending</span>';
    } else if (arg.event.extendedProps.status_meet === 'approved') {
        statusBadge = '<span class="badge badge-success">Approved</span>';
    } else if (arg.event.extendedProps.status_meet === 'rejected') {
        statusBadge = '<span class="badge badge-danger">Rejected</span>';
    }

    return {
        html: `
            <div class="fc-content p-2">
                <div class="fc-title"><strong>${arg.event.extendedProps.room_type}</strong></div>
                <div class="fc-description">Booked by: ${arg.event.extendedProps.name}</div>
                <div class="mt-1">${statusBadge}</div>
            </div>
        `
    };
},

eventDidMount: function(info) {
    // Tetapkan warna latar belakang dan border secara eksplisit
    $(info.el).css('background-color', info.event.backgroundColor);
    $(info.el).css('border-color', info.event.borderColor);

    // Tambahkan tooltip
    $(info.el).tooltip({
        title: `${info.event.extendedProps.room_type} - ${info.event.extendedProps.name}`,
        placement: 'top',
        trigger: 'hover',
        container: 'body'
    });
},

        // Locale dan business hours
        locale: 'en',
        businessHours: {
            daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Friday
            startTime: '07:00',
            endTime: '18:00',
        }
    });

    calendar.render();

    // Room Filter Handler
    $('#roomFilter').on('change', function() {
        selectedRoom = $(this).val();
        calendar.refetchEvents();
    });

    // Function to show booking details
    function showBookingDetails(event) {
        let statusBadge = '';
        if (event.extendedProps.status_meet === 'pending') {
            statusBadge = '<span class="badge badge-warning">Pending</span>';
        } else if (event.extendedProps.status_meet === 'approved') {
            statusBadge = '<span class="badge badge-success">Approved</span>';
        } else if (event.extendedProps.status_meet === 'rejected') {
            statusBadge = '<span class="badge badge-danger">Rejected</span>';
        }

        Swal.fire({
            title: 'Booking Details',
            html: `
                <div class="booking-details">
                    <p><strong>Room:</strong> ${event.extendedProps.room_type}</p>
                    <p><strong>Booked by:</strong> ${event.extendedProps.name}</p>
                    <p><strong>Date:</strong> ${moment(event.start).format('DD MMMM YYYY')}</p>
                    <p><strong>Time:</strong> ${moment(event.start).format('HH:mm')} - ${moment(event.end).format('HH:mm')}</p>
                    <p><strong>Total Participants:</strong> ${event.extendedProps.total_numbers}</p>
                    <p><strong>Purpose:</strong> ${event.extendedProps.message}</p>
                    <p><strong>Status:</strong> ${statusBadge}</p>
                </div>
            `,
            confirmButtonText: 'Close',
            customClass: {
                confirmButton: 'btn btn-primary'
            }
        });
    }
});
</script>

<style>
    .fc {
        background: white;
    }
    .fc-event {
        cursor: pointer;
        margin: 2px 0;
        padding: 2px;
        border-radius: 3px;
        /* Jangan tetapkan background-color di sini */
    }
    .fc-event:hover {
        opacity: 0.9;
    }
    .fc-event .badge {
        font-size: 0.8em;
    }
    .booking-details {
        text-align: left;
        margin: 10px;
    }
    .booking-details p {
        margin-bottom: 8px;
    }
    .fc-content {
        font-size: 0.9em;
        /* Hapus background-color dan color dari sini */
    }
    .badge {
        padding: 3px 6px;
        border-radius: 3px;
        font-size: 11px;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #000;
    }
    .badge-success {
        background-color: #28a745;
        color: #fff;
    }
    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }
    </style>
    
@endsection