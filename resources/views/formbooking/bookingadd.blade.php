@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Add Booking</h3>
                    </div>
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('form/booking/save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row formtype">
                            @if (Auth::user()->role_name == 'user')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            name="name" value="{{ old('name', Auth::user()->name ?? '') }}">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <select class="form-control @error('name') is-invalid @enderror" id="userDropdown"
                                            name="name" required>
                                            <option selected disabled>-- Select Name --</option>
                                            @foreach ($user as $user)
                                                <option value="{{ $user->id }}" data-phone="{{ $user->phone_number }}"
                                                    data-email="{{ $user->email }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <select class="form-control @error('room_type') is-invalid @enderror"
                                        id="roomTypeSelect" name="room_type" required>
                                        <option selected disabled> --Select Room Type-- </option>
                                        @foreach ($data as $item)
                                            <option value="{{ $item->room_type }}"
                                                {{ old('room_type') == $item->room_type ? 'selected' : '' }}>
                                                {{ $item->room_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Members</label>
                                    <input type="number" class="form-control @error('total_numbers') is-invalid @enderror"
                                        name="total_numbers" value="{{ old('total_numbers') }}" required>
                                    @error('total_numbers')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <div class="time-icon">
                                        <i class="far fa-clock position-absolute"
                                            style="top: 50%; transform: translateY(-50%); right: 10px; z-index: 2;"></i>
                                        <input type="time" class="form-control @error('time_start') is-invalid @enderror"
                                            name="time_start" value="{{ old('time_start') }}" required>
                                        @error('time_start')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <div class="time-icon">
                                        <i class="far fa-clock position-absolute"
                                            style="top: 50%; transform: translateY(-50%); right: 10px; z-index: 2;"></i>
                                        <input type="time" class="form-control @error('time_end') is-invalid @enderror"
                                            name="time_end" value="{{ old('time_end') }}" required>
                                        @error('time_end')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if (Auth::user()->role_name == 'superadmin' || Auth::user()->role_name == 'admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" id="emailInput"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->role_name == 'superadmin' || Auth::user()->role_name == 'admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" id="phoneInput"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            name="phone_number" value="{{ old('phone_number') }}" required>
                                        @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            name="phone_number"
                                            value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" required>
                                        @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Purpose</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" rows="3" name="message" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card room-preview d-none">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Room Info</h5>
                            </div>
                            <div class="card-body">
                                <img id="room-image" src="" alt="Room Image" class="img-fluid mb-3"
                                    style="width: 100%; height: 200px; object-fit: cover;">
                                <h5 id="room-type-display" class="card-title"></h5>
                                <p id="room-capacity" class="card-text mb-3"></p>
                                <div id="room-facilities">
                                    <h6 class="mb-2">Room Facilities:</h6>
                                    <div class="facilities-list">
                                        <div id="has-projector" class="facility-item d-none">
                                            <i class="fas fa-tv text-primary"></i>
                                            <span>LCD Projector</span>
                                        </div>
                                        <div id="has-sound" class="facility-item d-none">
                                            <i class="fas fa-volume-up text-primary"></i>
                                            <span>Sound System</span>
                                        </div>
                                        <div id="has-tv" class="facility-item d-none">
                                            <i class="fas fa-desktop text-primary"></i>
                                            <span>TV</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary buttonedit1">Create Booking</button>
            </form>
        </div>
    </div>

@section('script')
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                var startTime = $('input[name="time_start"]').val();
                var endTime = $('input[name="time_end"]').val();

                if (startTime && endTime && startTime >= endTime) {
                    e.preventDefault();
                    alert('End time must be after start time');
                    return false;
                }
            });

            $('#roomTypeSelect').on('change', function() {
                const selectedRoomType = $(this).val();
                if (!selectedRoomType) {
                    $('.room-preview').addClass('d-none');
                    return;
                }

                $.ajax({
                    url: '/api/room-details/' + encodeURIComponent(selectedRoomType),
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const room = response.room;
                            $('#room-image').attr('src', '/uploads/rooms/' + room.fileupload);
                            $('#room-type-display').text(room.room_type);
                            $('#room-capacity').text('Capacity: ' + room.capacity +
                                ' participants');
                            $('#has-projector').toggleClass('d-none', !room.has_projector);
                            $('#has-sound').toggleClass('d-none', !room.has_sound_system);
                            $('#has-tv').toggleClass('d-none', !room.has_tv);
                            $('.room-preview').removeClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Failed to fetch room details');
                        $('.room-preview').addClass('d-none');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.getElementById('userDropdown');
            const phoneInput = document.getElementById('phoneInput');
            const emailInput = document.getElementById('emailInput');

            userDropdown.addEventListener('change', function() {
                const selectedOption = userDropdown.options[userDropdown.selectedIndex];
                const phone = selectedOption.getAttribute('data-phone');
                const email = selectedOption.getAttribute('data-email');

                phoneInput.value = phone || '';
                emailInput.value = email || '';
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
