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
                <div class="col-12">
                    <form id="filterForm" action="{{ route('form/allbooking') }}" method="GET"
                        class="d-flex align-items-center flex-wrap">
                        <div class="d-flex align-items-center flex-wrap filter-group">
                            <div class="form-group d-flex align-items-center me-3">
                                <label for="month" class="me-2">Month:</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">All Months</option>
                                    @foreach ($months as $key => $month)
                                        <option value="{{ $key }}"
                                            {{ request('month') == $key ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-flex align-items-center me-3">
                                <label for="year" class="me-2">Year:</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">All Years</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary me-3"
                                onclick="window.location.href='{{ route('form/allbooking') }}'">
                                Reset Filter
                            </button>
                        </div>
                        <div class="search-container ms-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." id="searchBooking">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
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
                                                <td>
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bookings->phone_number) }}"
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
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($bookings->status_meet == 'Booked')
                                                        <span class="badge badge-warning">Booked</span>
                                                    @elseif($bookings->status_meet == 'In meeting')
                                                        <span class="badge badge-success">In meeting</span>
                                                    @elseif($bookings->status_meet == 'Finished')
                                                        <span class="badge badge-green">Finished</span>
                                                    @else
                                                        <span class="badge badge-danger">Cancel</span>
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
                                                                    @if ($bookings->status_meet != 'Finished' && $bookings->status_meet != 'cancel')
                                                                        <a class="dropdown-item"
                                                                            href="{{ url('form/booking/edit/' . $bookings->bkg_id) }}">
                                                                            <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                                        </a>
                                                                    @endif
                                                                    @if ($bookings->status_meet == 'Booked')
                                                                        <a class="dropdown-item cancelBooking"
                                                                            href="javascript:void(0);"
                                                                            data-id="{{ $bookings->bkg_id }}">
                                                                            <i class="fas fa-times-circle m-r-5"></i> Cancel
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
                                                                            data-toggle="modal"
                                                                            data-target="#delete_asset"
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
                            <img src="{{ URL::to('assets/img/sent.png') }}" alt="" width="50"
                                height="46">
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
            $(document).on('click', '.cancelBooking', function() {
                var bookingId = $(this).data('id');

                Swal.fire({
                    title: 'Cancel Booking Confirmation',
                    text: "Are you sure you want to cancel this booking?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('form.booking.cancel') }}',
                            type: 'POST',
                            data: {
                                id: bookingId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Booking Canceled!',
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
                                        response.message ||
                                        'Failed to cancel booking',
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
                        statusBadge = '<span class="badge badge-success">In meeting</span>';
                        break;
                    case 'cancel':
                        statusBadge = '<span class="badge badge-danger">Cancel</span>';
                        break;
                    default:
                        statusBadge = '<span class="badge badge-green">Finished</span>';
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

        //search
        // Add this inside your existing $(document).ready function
        $('#searchBooking').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.booking-row').filter(function() {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1 ||
                    $(this).data('name').toLowerCase().indexOf(value) > -1 ||
                    $(this).data('division').toLowerCase().indexOf(value) > -1 ||
                    $(this).data('room-type').toLowerCase().indexOf(value) > -1
                );
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

        .whatsapp-link {
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.2s ease;
        }

        .whatsapp-link:hover {
            transform: scale(1.1);
        }

        .whatsapp-icon {
            vertical-align: middle;
        }

        .search-box {
            min-width: 250px;
        }

        @media (max-width: 768px) {
            .search-box {
                width: 100%;
            }

            .form-group {
                margin-right: 0 !important;
                width: 100%;
            }

            .btn {
                width: 100%;
                margin-top: 10px;
            }

            .input-group {
                width: 100% !important;
            }
        }

        #filterForm {
            gap: 15px;
        }

        .form-group {
            margin: 0;
            white-space: nowrap;
        }

        .search-container {
            /* min-width: 250px; */
            /* margin-top: 1rem;
            margin-bottom: 1rem; */
            max-width: 300px;
            margin-left: auto;
        }

        .input-group {
            width: 100%;
        }

        .filter-group {
            gap: 15px;
        }

        .form-group label {
            white-space: nowrap;
        }

        .input-group-text {
            background-color: #fff;
            border-left: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        @media (max-width: 992px) {
            .search-container {
                width: 100%;
                margin-top: 15px;
            }

            .filter-group {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 768px) {
            .form-group {
                width: 100%;
                margin-bottom: 10px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection
@endsection
