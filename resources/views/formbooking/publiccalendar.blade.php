<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Calendar</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    
    <style>
        .fc-event {
            cursor: pointer;
        }
        .event-title {
            font-weight: bold;
            margin-bottom: 2px;
        }
        .event-info {
            font-size: 0.9em;
        }
        .badge {
            margin-top: 2px;
        }
        .page-wrapper {
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Room Booking Calendar</h3>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login to Book</a>
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
                            @foreach ($data as $room)
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

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
                slotMaxTime: '22:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00',
                eventDisplay: 'block',
                dayMaxEventRows: true,
                eventOverlap: false,
                slotEventOverlap: false,
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '{{ route('publiccalendar.events') }}',
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
                            console.error('Error fetching events:', error);
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
                    let statusBadge = '';
                    switch (arg.event.extendedProps.status_meet) {
                        case 'Booked':
                            statusBadge = '<span class="badge badge-warning">Booked</span>';
                            break;
                        case 'In meeting':
                            statusBadge = '<span class="badge badge-danger">In meeting</span>';
                            break;
                        case 'Finished':
                            statusBadge = '<span class="badge badge-success">Finished</span>';
                            break;
                    }

                    return {
                        html: `
                        <div class="fc-content">
                            <div class="event-title">${arg.event.extendedProps.room_type}</div>
                            <div class="event-info">
                                <span class="event-name">${arg.event.extendedProps.name}</span>
                                <span class="event-time">${moment(arg.event.start).format('HH:mm')} - ${moment(arg.event.end).format('HH:mm')}</span>
                            </div>
                            <div class="event-status">${statusBadge}</div>
                        </div>
                    `
                    };
                },
                nowIndicator: true,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5],
                    startTime: '07:00',
                    endTime: '22:00',
                },
                scrollTime: '07:00:00',
                height: 'auto',
                expandRows: true,
                stickyHeaderDates: true,
                firstDay: 1
            });

            calendar.render();

            $('#roomFilter').on('change', function() {
                selectedRoom = $(this).val();
                calendar.refetchEvents();
            });

            function showBookingDetails(event) {
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
                            <p><strong>Status:</strong> ${event.extendedProps.status_meet}</p>
                        </div>
                    `,
                    confirmButtonText: 'Close'
                });
            }
        });
    </script>
</body>
</html>