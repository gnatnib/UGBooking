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
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Name</th>
                                            <th>Division</th>
                                            <th>Room Type</th>
                                            <th>Total Numbers</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allBookings as $bookings)
                                        <tr>
                                            <td>{{ $bookings->bkg_id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#">{{ $bookings->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $bookings->division }}</td>
                                            <td>{{ $bookings->room_type }}</td>
                                            <td>{{ $bookings->total_numbers }} Person</td>
                                            <td>{{ \Carbon\Carbon::parse($bookings->date)->format('Y-m-d') }}</td>
                                            <td>{{ $bookings->time_start }} - {{ $bookings->time_end }}</td>
                                            <td>{{ $bookings->email }}</td>
                                            <td>{{ $bookings->phone_number }}</td>
                                            <td>
                                                @if($bookings->status_meet == 'Booked')
                                                    <span class="badge badge-warning">Booked</span>
                                                @elseif($bookings->status_meet == 'In meeting')
                                                    <span class="badge badge-danger">In meeting</span>
                                                @else
                                                    <span class="badge badge-success">Finished</span>
                                                @endif
                                            </td>
                                            @if($bookings->status_meet != 'Finished')
                                                <td class="text-right">
                                                    {{-- Tampilkan aksi hanya untuk admin/superadmin atau pemilik booking --}}
                                                    @if(Auth::user()->role_name == 'admin' || Auth::user()->role_name == 'superadmin' || Auth::user()->name == $bookings->name)
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                @if ($bookings->status_meet != 'Finished')   
                                                                    <a class="dropdown-item" href="{{ url('form/booking/edit/'.$bookings->bkg_id) }}">
                                                                        <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                                    </a>
                                                                @endif
                                                                @if($bookings->status_meet == 'In meeting')
                                                                    <a class="dropdown-item endMeeting" href="javascript:void(0);" data-id="{{ $bookings->bkg_id }}">
                                                                        <i class="fas fa-stop-circle m-r-5"></i> End Meeting
                                                                    </a>
                                                                @endif
                                                                @if ($bookings->status_meet != 'Finished'||Auth::user()->role_name == 'superadmin')   
                                                                    <a class="dropdown-item bookingDelete" data-toggle="modal" data-target="#delete_asset" data-id="{{ $bookings->bkg_id }}">
                                                                        <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
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
            // Setup CSRF token for all AJAX requests
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
                            url: '{{ route("form/booking/endMeeting") }}',
                            type: 'POST',
                            data: {
                                id: bookingId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Server response:', response);
                                
                                if(response.success) {
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
                                console.error('AJAX Error:', error);
                                console.error('Response:', xhr.responseText);
                                
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
        });
    </script>
    <script>
        $(document).on('click', '.bookingDelete', function() {
            var id = $(this).data('id');
            $('#e_id').val(id);
        });
    </script>
@endsection
@endsection