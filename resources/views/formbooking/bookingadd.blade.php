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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <select class="form-control @error('name') is-invalid @enderror" id="sel1"
                                        name="name" required>
                                        <option selected disabled> --Select Name-- </option>
                                        @foreach ($user as $users)
                                            <option value="{{ $users->name }}"
                                                {{ old('name') == $users->name ? 'selected' : '' }}>
                                                {{ $users->name }}
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <select class="form-control @error('room_type') is-invalid @enderror" id="sel2"
                                        name="room_type" required>
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
                                        <input type="text"
                                            class="form-control datetimepicker @error('date') is-invalid @enderror"
                                            name="date" value="{{ old('date') }}" required>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" value="{{ old('phone_number') }}" required>
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
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

                    <!-- Room Preview Card -->
                    <div class="col-lg-4">
                        <div class="card room-preview d-none">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Room Info</h5>
                            </div>
                            <img id="room-image" src="" alt="Room Image" class="card-img-top p-2"
                                style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 id="room-type-display" class="card-title"></h5>
                                <p id="room-capacity" class="card-text"></p>
                                <div id="room-facilities" class="mt-2">
                                    <h6>Room Facility:</h6>
                                    <ul class="list-unstyled">
                                        <li id="has-projector" class="d-none">
                                            <i class="fa fa-check text-success"></i> LCD Projector
                                        </li>
                                        <li id="has-sound" class="d-none">
                                            <i class="fa fa-check text-success"></i> Sound System
                                        </li>
                                        <li id="has-tv" class="d-none">
                                            <i class="fa fa-check text-success"></i> TV
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary buttonedit1">Create Booking</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Time validation
            $('form').on('submit', function(e) {
                var startTime = $('input[name="time_start"]').val();
                var endTime = $('input[name="time_end"]').val();

                if (startTime && endTime && startTime >= endTime) {
                    e.preventDefault();
                    alert('End time must be after start time');
                    return false;
                }
            });

            // Handle room type selection
            $('select[name="room_type"]').on('change', function() {
                const selectedRoomType = $(this).val();
                if (!selectedRoomType) {
                    $('.room-preview').addClass('d-none');
                    return;
                }

                // Fetch room details using AJAX
                $.ajax({
                    url: '/api/room-details/' + encodeURIComponent(selectedRoomType),
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            const room = response.room;

                            // Update room preview
                            $('#room-image').attr('src', '/assets/upload/' + room.fileupload);
                            $('#room-type-display').text(room.room_type);
                            $('#room-capacity').text('Capacity: ' + room.capacity +
                                ' participants');

                            // Update facilities
                            $('#has-projector').toggleClass('d-none', !room.has_projector);
                            $('#has-sound').toggleClass('d-none', !room.has_sound_system);
                            $('#has-tv').toggleClass('d-none', !room.has_tv);

                            // Show the preview card
                            $('.room-preview').removeClass('d-none');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch room details');
                        $('.room-preview').addClass('d-none');
                    }
                });
            });

            // Initialize datepicker
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
