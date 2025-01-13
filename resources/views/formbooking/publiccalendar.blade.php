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
        .fc {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .fc-header-toolbar {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }

        .fc-view-harness {
            padding: 0.5rem;
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            margin: 1px !important;
            border-radius: 4px;
            overflow: hidden;
            width: calc(100% - 4px) !important;
            background: transparent;
        }

        .fc-content {
            padding: 4px 6px;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .event-title {
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 2px;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }

        .event-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .event-name,
        .event-time {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-status {
            margin-top: 2px;
        }

        .fc-timegrid-slot {
            height: 3em !important;
        }

        .badge {
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: normal;
            border-radius: 3px;
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

        /* Room-specific colors */
        .fc-event[data-room="Meeting Room A"] {
            background: linear-gradient(to right, #4e73df, #3a5fc7);
        }

        .fc-event[data-room="Meeting Room B"] {
            background: linear-gradient(to right, #1cc88a, #13a673);
        }

        .fc-event[data-room="Conference Room"] {
            background: linear-gradient(to right, #f6c23e, #e5b53a);
        }

        .fc-event[data-room="Discussion Room"] {
            background: linear-gradient(to right, #e74a3b, #d63a2d);
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .tooltip {
            font-size: 0.85rem;
            text-align: center;
            background-color: #333;
            color: #fff;
            border-radius: 4px;
            padding: 8px;
            line-height: 1.5;
        }

        .tooltip .tooltip-inner {
            max-width: 200px;
            white-space: normal;
        }

        .page-wrapper {
            padding: 20px;
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .fc-header-toolbar {
                flex-direction: column;
            }

            .fc-toolbar-chunk {
                margin-bottom: 0.5rem;
            }

            .event-title {
                font-size: 0.75rem;
            }

            .event-info {
                font-size: 0.7rem;
            }
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

                    const startTime = new Date(arg.event.start).toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                    const endTime = new Date(arg.event.end).toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                    return {
                        html: `
                        <div class="fc-content">
                            <div class="event-title">${arg.event.extendedProps.room_type}</div>
                            <div class="event-info">
                                <span class="event-name">${arg.event.extendedProps.name}</span>
                                <span class="event-time">${startTime} - ${endTime}</span>
                            </div>
                            <div class="event-status">${statusBadge}</div>
                        </div>
                        `
                    };
                },
                eventDidMount: function(info) {
                    info.el.setAttribute('data-room', info.event.extendedProps.room_type);

                    $(info.el).tooltip({
                        title: `
                        <div style="display: flex; flex-direction: column; text-align: center;">
                            <strong>Room:</strong> ${info.event.extendedProps.room_type}<br>
                            <strong>Booked by:</strong> ${info.event.extendedProps.name}<br>
                            <strong>Time:</strong> ${moment(info.event.start).format('HH:mm')} - ${moment(info.event.end).format('HH:mm')}<br>
                            <strong>Participants:</strong> ${info.event.extendedProps.total_numbers}
                        </div>
                        `,
                        placement: 'top',
                        trigger: 'hover',
                        html: true,
                        container: 'body'
                    });
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
                firstDay: 1,
                locale: 'en',
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }
            });

            calendar.render();

            $('#roomFilter').on('change', function() {
                selectedRoom = $(this).val();
                calendar.refetchEvents();
            });

            function showBookingDetails(event) {
                const startTime = new Date(event.start).toLocaleTimeString('en-GB', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                const endTime = new Date(event.end).toLocaleTimeString('en-GB', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                const dateStr = new Date(event.start).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });

                let statusBadge = '';
                switch (event.extendedProps.status_meet) {
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

                Swal.fire({
                    title: 'Booking Details',
                    html: `
                        <div class="booking-details">
                            <p><strong>Room:</strong> ${event.extendedProps.room_type}</p>
                            <p><strong>Booked by:</strong> ${event.extendedProps.name}</p>
                            <p><strong>Date:</strong> ${dateStr}</p>
                            <p><strong>Time:</strong> ${startTime} - ${endTime}</p>
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
</body>

</html>
