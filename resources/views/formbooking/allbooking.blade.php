@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">Booking List</h4>
                            <a href="{{ route('form/booking/add') }}" class="btn btn-primary float-right veiwbutton">
                                <i class="fas fa-plus"></i> Add Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <form id="filterForm" action="{{ route('form/allbooking') }}" method="GET" class="form-inline">
                        <div class="form-group mx-sm-3">
                            <label for="month" class="mr-2">Month:</label>
                            <select name="month" id="month" class="form-control">
                                <option value="">All Months</option>
                                @foreach ($months as $key => $month)
                                    <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mx-sm-3">
                            <label for="year" class="mr-2">Year:</label>
                            <select name="year" id="year" class="form-control">
                                <option value="">All Years</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('form/allbooking') }}" class="btn btn-secondary">Reset Filter</a>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Name</th>
                                            <th>Division</th>
                                            <th>Room Type</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allBookings as $bookings)
                                            <tr class="booking-row" data-booking-id="{{ $bookings->bkg_id }}"
                                                data-name="{{ $bookings->name }}"
                                                data-division="{{ $bookings->division }}"
                                                data-room-type="{{ $bookings->room_type }}"
                                                data-total-numbers="{{ $bookings->total_numbers }}"
                                                data-date="{{ \Carbon\Carbon::parse($bookings->date)->format('Y-m-d') }}"
                                                data-time-start="{{ $bookings->time_start }}"
                                                data-time-end="{{ $bookings->time_end }}"
                                                data-email="{{ $bookings->email }}"
                                                data-phone="{{ $bookings->phone_number }}"
                                                data-status="{{ $bookings->status_meet }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($bookings->date)->format('Y-m-d') }}</td>
                                                <td>{{ $bookings->time_start }} - {{ $bookings->time_end }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#">{{ $bookings->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $bookings->division }}</td>
                                                <td>{{ $bookings->room_type }}</td>
                                                <td>{{ $bookings->phone_number }}</td>
                                                <td>
                                                    @if ($bookings->status_meet == 'Booked')
                                                        <span class="badge badge-warning">Booked</span>
                                                    @elseif($bookings->status_meet == 'In meeting')
                                                        <span class="badge badge-danger">In meeting</span>
                                                    @else
                                                        <span class="badge badge-success">Finished</span>
                                                    @endif
                                                </td>

                                                <td class="text-right">
                                                    @if (Auth::user()->role_name == 'admin' ||
                                                            Auth::user()->role_name == 'superadmin' ||
                                                            Auth::user()->name == $bookings->name)
                                                        @if ($bookings->status_meet != 'Finished')
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if ($bookings->status_meet != 'Finished')
                                                                        <a class="dropdown-item"
                                                                            href="{{ url('form/booking/edit/' . $bookings->bkg_id) }}">
                                                                            <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                                        </a>
                                                                    @endif
                                                                    @if ($bookings->status_meet == 'In meeting')
                                                                        <a class="dropdown-item endMeeting"
                                                                            href="javascript:void(0);"
                                                                            data-id="{{ $bookings->bkg_id }}">
                                                                            <i class="fas fa-stop-circle m-r-5"></i> End
                                                                            Meeting
                                                                        </a>
                                                                    @endif
                                                                    @if (Auth::user()->role_name == 'superadmin')
                                                                        <a class="dropdown-item bookingDelete"
                                                                            data-toggle="modal" data-target="#delete_asset"
                                                                            data-id="{{ $bookings->bkg_id }}">
                                                                            <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
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

        {{-- Delete Modal --}}
        <div id="delete_asset" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('form/booking/delete') }}" method="POST">
                        @csrf
                        <div class="modal-body text-center">
                            <img src="{{ URL::to('assets/img/sent.png') }}" alt="" width="50" height="46">
                            <h3 class="delete_class">Are you sure want to delete this booking?</h3>
                            <div class="m-t-20">
                                <a href="#" class="btn btn-secondary" data-dismiss="modal">Close</a>
                                <input type="hidden" id="e_id" name="id" value="">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // End Meeting handler
            $(document).on('click', '.endMeeting', function() {
                var bookingId = $(this).data('id');

                Swal.fire({
                    title: 'End Meeting Confirmation',
                    text: "Are you sure you want to end this meeting now?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, end meeting!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('form/booking/endMeeting') }}',
                            type: 'POST',
                            data: {
                                id: bookingId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Meeting Ended!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message || 'Failed to end meeting',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to communicate with server',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Booking details modal handler 
            $(document).on('click', '.booking-row', function(e) {
                if ($(e.target).closest('.dropdown-action').length) {
                    return;
                }

                const bookingDetails = {
                    id: $(this).data('booking-id'),
                    name: $(this).data('name'),
                    division: $(this).data('division'),
                    roomType: $(this).data('room-type'),
                    totalNumbers: $(this).data('total-numbers'),
                    date: $(this).data('date'),
                    timeStart: $(this).data('time-start'),
                    timeEnd: $(this).data('time-end'),
                    email: $(this).data('email'),
                    phone: $(this).data('phone'),
                    status: $(this).data('status')
                };

                let statusBadge = '';
                switch (bookingDetails.status) {
                    case 'Booked':
                        statusBadge = '<span class="badge badge-warning">Booked</span>';
                        break;
                    case 'In meeting':
                        statusBadge = '<span class="badge badge-danger">In meeting</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge badge-success">Finished</span>';
                }

                Swal.fire({
                    title: 'Booking Details',
                    html: `
                       <div class="booking-details">
                           <p><strong>Room:</strong> ${bookingDetails.roomType}</p>
                           <p><strong>Booked by:</strong> ${bookingDetails.name}</p>
                           <p><strong>Division:</strong> ${bookingDetails.division}</p>
                           <p><strong>Date:</strong> ${bookingDetails.date}</p>
                           <p><strong>Time:</strong> ${bookingDetails.timeStart} - ${bookingDetails.timeEnd}</p>
                           <p><strong>Total Participants:</strong> ${bookingDetails.totalNumbers}</p>
                           <p><strong>Email:</strong> ${bookingDetails.email}</p>
                           <p><strong>Phone:</strong> ${bookingDetails.phone}</p>
                           <p><strong>Status:</strong> ${statusBadge}</p>
                       </div>
                   `,
                    confirmButtonText: 'Close',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });

            // Booking delete handler
            $(document).on('click', '.bookingDelete', function() {
                var id = $(this).data('id');
                $('#e_id').val(id);
            });
        });

        $(document).ready(function() {
            // Auto submit on select change
            $('#month, #year').on('change', function() {
                $('#filterForm').submit();
            });
        });
    </script>

    <style>
        .form-inline {
            display: flex;
            align-items: center;
        }

        .form-inline .form-group {
            margin-bottom: 0;
        }

        .form-control {
            height: 38px;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mx-sm-3 {
            margin-left: 1rem;
            margin-right: 1rem;
        }

        .booking-details {
            text-align: left;
            padding: 1rem;
        }

        .booking-details p {
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-details p strong {
            color: #555;
            min-width: 140px;
        }

        .booking-details .badge {
            padding: 5px 10px;
            font-size: 0.75rem;
            font-weight: normal;
            border-radius: 4px;
        }

        .booking-row {
            cursor: pointer;
        }

        .booking-row:hover {
            background-color: #f5f5f5;
        }
    </style>
@endsection
@endsection
