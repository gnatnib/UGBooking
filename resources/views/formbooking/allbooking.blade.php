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
                                            <td>{{ $bookings->room_type }}</td>
                                            <td>{{ $bookings->total_numbers }} Person</td>
                                            <td>{{ \Carbon\Carbon::parse($bookings->date)->format('d M Y') }}</td>
                                            <td>{{ $bookings->time_start }} - {{ $bookings->time_end }}</td>
                                            <td>{{ $bookings->email }}</td>
                                            <td>{{ $bookings->phone_number }}</td>
                                            <td>
                                                @if($bookings->status_meet == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($bookings->status_meet == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{-- Tampilkan aksi hanya untuk admin/superadmin atau pemilik booking --}}
                                                @if(Auth::user()->role_name == 'admin' || Auth::user()->role_name == 'superadmin' || Auth::user()->name == $bookings->name)
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="{{ url('form/booking/edit/'.$bookings->bkg_id) }}">
                                                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item bookingDelete" data-toggle="modal" data-target="#delete_asset" data-id="{{ $bookings->bkg_id }}">
                                                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
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
    <script>
        $(document).on('click', '.bookingDelete', function() {
            var id = $(this).data('id');
            $('#e_id').val(id);
        });
    </script>
@endsection
@endsection