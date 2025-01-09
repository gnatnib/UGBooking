@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Booking</h3>
                    </div>
                </div>
            </div>

            {{-- Tampilkan pesan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('form/booking/update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Booking ID</label>
                                    <input class="form-control @error('bkg_id') is-invalid @enderror" 
                                           type="text" name="bkg_id" value="{{ $bookingEdit->bkg_id }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" name="name" value="{{ old('name', $bookingEdit->name) }}" 
                                           {{ Auth::user()->role_name == 'user' ? 'readonly' : '' }}>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <select class="form-control @error('room_type') is-invalid @enderror" name="room_type">
                                        @foreach($data as $room)
                                            <option value="{{ $room->room_type }}" 
                                                {{ old('room_type', $bookingEdit->room_type) == $room->room_type ? 'selected' : '' }}>
                                                {{ $room->room_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Members</label>
                                    <input class="form-control @error('total_numbers') is-invalid @enderror" 
                                           type="number" name="total_numbers" 
                                           value="{{ old('total_numbers', $bookingEdit->total_numbers) }}">
                                    @error('total_numbers')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="cal-icon">
                                        <input type="text" class="form-control datetimepicker @error('date') is-invalid @enderror" 
                                               name="date" value="{{ old('date', $bookingEdit->date) }}">
                                        @error('date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <div class="time-icon">
                                        <input type="time" class="form-control @error('time_start') is-invalid @enderror" 
                                               name="time_start" value="{{ old('time_start', $bookingEdit->time_start) }}">
                                        @error('time_start')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <div class="time-icon">
                                        <input type="time" class="form-control @error('time_end') is-invalid @enderror" 
                                               name="time_end" value="{{ old('time_end', $bookingEdit->time_end) }}">
                                        @error('time_end')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $bookingEdit->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                           name="phone_number" value="{{ old('phone_number', $bookingEdit->phone_number) }}">
                                    @error('phone_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              name="message" rows="1.5">{{ old('message', $bookingEdit->message) }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if(Auth::user()->role_name == 'admin' || Auth::user()->role_name == 'superadmin')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control @error('status_meet') is-invalid @enderror" name="status_meet">
                                            <option value="pending" {{ old('status_meet', $bookingEdit->status_meet) == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="approved" {{ old('status_meet', $bookingEdit->status_meet) == 'approved' ? 'selected' : '' }}>
                                                Approved
                                            </option>
                                            <option value="rejected" {{ old('status_meet', $bookingEdit->status_meet) == 'rejected' ? 'selected' : '' }}>
                                                Rejected
                                            </option>
                                        </select>
                                        @error('status_meet')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary buttonedit mt-3">Update Booking</button>
                <a href="{{ route('form/allbooking') }}" class="btn btn-secondary mt-3 ml-2">Cancel</a>
            </form>
        </div>
    </div>

    @section('script')
    <script>
        $(function() {
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                minDate: new Date(),
                icons: {
                    up: "fas fa-chevron-up",
                    down: "fas fa-chevron-down",
                    next: 'fas fa-chevron-right',
                    previous: 'fas fa-chevron-left'
                }
            });
        });
    </script>
    @endsection
@endsection