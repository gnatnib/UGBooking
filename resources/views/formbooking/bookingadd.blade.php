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
                                            name="name" value="{{ old('name', Auth::user()->name ?? '') }}" readonly>
                                        <input type="hidden" name="division" value="{{ Auth::user()->division ?? '' }}">
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
                                            name="user_id" required>
                                            <option disabled>-- Select Name --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                                    data-division="{{ $user->division }}"
                                                    data-phone="{{ $user->phone_number }}" data-email="{{ $user->email }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="name" id="hiddenNameInput"
                                            value="{{ old('name') }}">
                                        <input type="hidden" name="division" id="hiddenDivisionInput"
                                            value="{{ old('division') }}">
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
                                            name="email" value="{{ old('email', Auth::user()->email ?? '') }}"
                                            readonly>
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
                                            value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" readonly>
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
                                <!-- Image Carousel -->
                                <div id="room-images-carousel" class="carousel slide mb-3" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <!-- Will be populated by JavaScript -->
                                    </div>
                                    <a class="carousel-control-prev" href="#room-images-carousel" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#room-images-carousel" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>
                                    <ol class="carousel-indicators">
                                        <!-- Will be populated by JavaScript -->
                                    </ol>
                                </div>

                                <!-- Room Info -->
                                <h5 id="room-type-display" class="card-title"></h5>
                                <p id="room-capacity" class="card-text mb-3"></p>

                                <!-- Facilities -->
                                <div id="room-facilities">
                                    <h6 class="mb-2">Room Facilities:</h6>
                                    <div class="facilities-list">
                                        <!-- Will be populated by JavaScript -->
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
            // Form validation for time
            $('form').on('submit', function(e) {
                var startTime = $('input[name="time_start"]').val();
                var endTime = $('input[name="time_end"]').val();

                if (startTime && endTime && startTime >= endTime) {
                    e.preventDefault();
                    alert('End time must be after start time');
                    return false;
                }
            });

            // Check if room type is already selected (for validation errors)
            const selectedRoomType = $('#roomTypeSelect').val();
            if (selectedRoomType && selectedRoomType !== '--Select Room Type--') {
                loadRoomPreview(selectedRoomType);
            }

            // Room type selection handling
            $('#roomTypeSelect').on('change', function() {
                const selectedRoomType = $(this).val();
                if (!selectedRoomType || selectedRoomType === '--Select Room Type--') {
                    $('.room-preview').addClass('d-none');
                    return;
                }
                loadRoomPreview(selectedRoomType);
            });

            function loadRoomPreview(selectedRoomType) {
                $.ajax({
                    url: '/api/room-details/' + encodeURIComponent(selectedRoomType),
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const room = response.room;

                            // Update images carousel
                            const carouselInner = $('.carousel-inner');
                            const carouselIndicators = $('.carousel-indicators');
                            carouselInner.empty();
                            carouselIndicators.empty();

                            const images = JSON.parse(room.images);
                            images.forEach((image, index) => {
                                carouselInner.append(`
                                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                        <img src="/uploads/rooms/${image}" 
                                            class="d-block w-100" 
                                            alt="Room Image ${index + 1}">
                                    </div>
                                `);

                                carouselIndicators.append(`
                                    <li data-target="#room-images-carousel" 
                                        data-slide-to="${index}" 
                                        class="${index === 0 ? 'active' : ''}">
                                    </li>
                                `);
                            });

                            // Show/hide carousel controls based on number of images
                            if (images.length <= 1) {
                                $('.carousel-control-prev, .carousel-control-next, .carousel-indicators')
                                    .hide();
                            } else {
                                $('.carousel-control-prev, .carousel-control-next, .carousel-indicators')
                                    .show();
                            }

                            // Update room info
                            $('#room-type-display').text(room.room_type);
                            $('#room-capacity').text(`Capacity: ${room.capacity} participants`);

                            // Update facilities
                            const facilitiesList = $('.facilities-list');
                            facilitiesList.empty();

                            const facilities = JSON.parse(room.facilities);
                            facilities.forEach(facility => {
                                facilitiesList.append(`
                                    <div class="facility-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>${facility}</span>
                                    </div>
                                `);
                            });

                            $('.room-preview').removeClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Failed to fetch room details');
                    }
                });
            }

            // User dropdown handler
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                // Function to update hidden fields based on selected option
                function updateHiddenFields() {
                    const selectedOption = userDropdown.options[userDropdown.selectedIndex];
                    if (selectedOption && !selectedOption.disabled) {
                        const phone = selectedOption.getAttribute('data-phone');
                        const email = selectedOption.getAttribute('data-email');
                        const name = selectedOption.getAttribute('data-name');
                        const division = selectedOption.getAttribute('data-division');

                        document.getElementById('phoneInput').value = phone || '';
                        document.getElementById('emailInput').value = email || '';
                        document.getElementById('hiddenNameInput').value = name || '';
                        document.getElementById('hiddenDivisionInput').value = division || '';
                    }
                }

                // Update fields when dropdown changes
                userDropdown.addEventListener('change', updateHiddenFields);

                // Update fields on page load if there's a selected value
                updateHiddenFields();
            }
        });
    </script>


    <style>
        .position-relative {
            position: relative;
        }

        .calendar-icon {
            z-index: 1;
        }

        input[type="date"] {
            position: relative;
            z-index: 2;
            background: transparent;
        }

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

        /* Time icon styling */
        .time-icon {
            position: relative;
        }

        .time-icon i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #999;
            z-index: 2;
            pointer-events: none;
        }

        .time-icon input {
            cursor: text;
        }

        input[type="time"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            width: 100%;
            height: 100%;
            position: absolute;
            right: 0;
            top: 0;
            cursor: pointer;
        }

        /* Validation styling */
        .form-control.is-invalid {
            background-image: none !important;
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            position: absolute;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
@endsection
@endsection
