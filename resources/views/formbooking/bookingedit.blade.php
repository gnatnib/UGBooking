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
                                    <input class="form-control @error('bkg_id') is-invalid @enderror" type="text"
                                        name="bkg_id" value="{{ $bookingEdit->bkg_id }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        name="name" value="{{ old('name', $bookingEdit->name) }}"
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
                                        @foreach ($data as $room)
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
                                    <input class="form-control @error('total_numbers') is-invalid @enderror" type="number"
                                        name="total_numbers"
                                        value="{{ old('total_numbers', $bookingEdit->total_numbers) }}">
                                    @error('total_numbers')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="cal-icon">
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            name="date" value="{{ old('date') }}" pattern="\d{4}-\d{2}-\d{2}"
                                            required>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                    <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="1.5">{{ old('message', $bookingEdit->message) }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                          
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
    <style>
        .position-relative {
            position: relative;
        }

        .calendar-icon {
            z-index: 1;
        }

        /* Ensure the date input is fully clickable */
        input[type="date"] {
            position: relative;
            z-index: 2;
            background: transparent;
        }

        /* Hide the default calendar icon in Webkit browsers */
        input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .room-preview {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .facilities-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .facility-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .facility-item i {
            font-size: 18px;
        }

        /* Update the time-icon related styles in your style section */
        .time-icon {
            position: relative;
        }

        /* Remove the pointer cursor from the input */
        .time-icon input {
            cursor: text;
        }

        /* Style only the clock icon */
        .time-icon i {
            color: #999;
            cursor: pointer;
            pointer-events: all;
            /* Ensures the icon is clickable */
        }

        .time-icon:hover i {
            color: #666;
        }

        /* Keep the time picker clickable but don't affect input cursor */
        input[type="time"]::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0;
            right: 0;
            width: 2.5rem;
            /* Limit clickable area to just the icon area */
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }
    </style>
@endsection
@endsection