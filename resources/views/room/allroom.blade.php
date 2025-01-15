@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            {{-- Message --}}
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">All Rooms</h4>
                            <a href="{{ route('form/addroom/page') }}" class="btn btn-primary float-right veiwbutton">Add
                                Room</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Room Type</th>
                                            <th>Images</th>
                                            <th>Capacity</th>
                                            <th>Facilities</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allRooms as $rooms)
                                            <tr>
                                                <td hidden class="id">{{ $rooms->id }}</td>
                                                <td>{{ $rooms->room_type }}</td>
                                                <td>
                                                    @if ($rooms->images)
                                                        <div class="room-carousel">
                                                            <div id="carousel-{{ $rooms->id }}" class="carousel slide"
                                                                data-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @foreach (json_decode($rooms->images) as $index => $image)
                                                                        <div
                                                                            class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                                            <img src="{{ asset('uploads/rooms/' . $image) }}"
                                                                                alt="{{ $rooms->room_type }}"
                                                                                class="d-block w-100"
                                                                                style="height: 150px; object-fit: cover;">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                @if (count(json_decode($rooms->images)) > 1)
                                                                    <a class="carousel-control-prev"
                                                                        href="#carousel-{{ $rooms->id }}" role="button"
                                                                        data-slide="prev">
                                                                        <span class="carousel-control-prev-icon"></span>
                                                                    </a>
                                                                    <a class="carousel-control-next"
                                                                        href="#carousel-{{ $rooms->id }}" role="button"
                                                                        data-slide="next">
                                                                        <span class="carousel-control-next-icon"></span>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        No Images
                                                    @endif
                                                </td>
                                                <td>{{ $rooms->capacity }}</td>
                                                <td>
                                                    @if ($rooms->facilities)
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @php
                                                                $facilities = json_decode($rooms->facilities);
                                                                $maxDisplay = 3;
                                                                $displayedFacilities = array_slice(
                                                                    $facilities,
                                                                    0,
                                                                    $maxDisplay,
                                                                );
                                                                $remainingCount = count($facilities) - $maxDisplay;
                                                            @endphp

                                                            @foreach ($displayedFacilities as $facility)
                                                                <span class="badge badge-info facility-badge"
                                                                    data-id="{{ $rooms->id }}"
                                                                    data-room-type="{{ $rooms->room_type }}"
                                                                    data-capacity="{{ $rooms->capacity }}"
                                                                    data-facilities="{{ implode(', ', $facilities) }}">
                                                                    {{ $facility }}
                                                                </span>
                                                            @endforeach

                                                            @if ($remainingCount > 0)
                                                                <span class="badge badge-secondary facility-badge"
                                                                    data-id="{{ $rooms->id }}"
                                                                    data-room-type="{{ $rooms->room_type }}"
                                                                    data-capacity="{{ $rooms->capacity }}"
                                                                    data-facilities="{{ implode(', ', $facilities) }}"
                                                                    title="Click to view all facilities">
                                                                    +{{ $remainingCount }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($rooms->status == 'Ready')
                                                        <div class="actions">
                                                            <a href="#"
                                                                class="btn btn-sm bg-success-light mr-2">{{ $rooms->status }}</a>
                                                        </div>
                                                    @elseif ($rooms->status == 'Maintenance')
                                                        <div class="actions">
                                                            <a href="#"
                                                                class="btn btn-sm bg-danger-light mr-2">{{ $rooms->status }}</a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown dropdown-action items-center text-center">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color items-center"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item"
                                                                href="{{ url('form/room/edit/' . $rooms->bkg_room_id) }}">
                                                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item delete_asset" href="#"
                                                                data-toggle="modal" data-target="#delete_asset">
                                                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
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

        {{-- delete modal --}}
        <div id="delete_asset" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <form action="{{ route('form/room/delete') }}" method="POST">
                            @csrf
                            <img src="{{ URL::to('assets/img/sent.png') }}" alt="" width="50" height="46">
                            <h3 class="delete_class">Are you sure want to delete this Room?</h3>
                            <div class="m-t-20">
                                <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                                <input class="form-control" type="hidden" id="e_id" name="id"
                                    value="">
                                <input class="form-control" type="hidden" id="e_images" name="images"
                                    value="">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('script')
    <script>
        $(document).ready(function() {
            $('.datatable tbody').on('click', 'tr', function(e) {
                if ($(e.target).closest('.room-carousel, .dropdown-action').length) {
                    return;
                }

                const allFacilities = $(this).find('.facility-badge:first')
                    .data('facilities')
                    .split(', ')
                    .join(', ');

                const statusBtn = $(this).find('td .actions .btn').first();
                const status = statusBtn.text().trim();
                const statusClass = status === 'Ready' ? 'bg-success-light' : 'bg-danger-light';

                // Fix capacity retrieval by getting it directly from the capacity column
                const capacity = $(this).find('td:nth-child(4)').text()
            .trim(); // Changed from 3 to 4 since there's a hidden column

                const roomType = $(this).find('td:first').next().text().trim();

                const roomDetails = {
                    roomType: roomType,
                    capacity: capacity,
                    facilities: allFacilities,
                    status: status
                };

                Swal.fire({
                    title: `Detail Ruangan - ${roomDetails.roomType}`,
                    html: `
                <div class="room-details">
                    <p><strong>Tipe Ruangan:</strong> <span>${roomDetails.roomType}</span></p>
                    <p><strong>Kapasitas:</strong> <span>${roomDetails.capacity} orang</span></p>
                    <p class="facilities-row"><strong>Fasilitas:</strong> <span>${roomDetails.facilities || 'Tidak ada fasilitas'}</span></p>
                    <p><strong>Status:</strong> <span><span class="btn ${statusClass}">${roomDetails.status}</span></span></p>
                </div>
            `,
                    confirmButtonText: 'Tutup',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.carousel-item img').on('click', function(e) {
                e.stopPropagation();

                const carouselId = $(this).closest('.carousel').attr('id');
                const allImages = $(`#${carouselId} .carousel-item img`).map(function() {
                    return {
                        src: $(this).attr('src'),
                        alt: $(this).attr('alt')
                    };
                }).get();

                const currentIndex = allImages.findIndex(img => img.src === $(this).attr('src'));

                const showNavigationButtons = (index, total) => {
                    return `
                ${index > 0 ? '<button class="swal2-nav swal2-prev">‹</button>' : ''}
                ${index < total - 1 ? '<button class="swal2-nav swal2-next">›</button>' : ''}
            `;
                };

                const showImage = (index) => {
                    Swal.fire({
                        html: `
                    <div class="swal2-image-container">
                        <img src="${allImages[index].src}" class="swal2-image" alt="${allImages[index].alt}">
                        ${showNavigationButtons(index, allImages.length)}
                    </div>
                `,
                        title: allImages[index].alt,
                        showConfirmButton: false,
                        showCancelButton: false,
                        showCloseButton: true,
                        width: '80%',
                        customClass: {
                            popup: 'image-viewer-popup',
                            image: 'img-fluid'
                        },
                        didRender: () => {
                            $('.swal2-next').on('click', () => {
                                if (index < allImages.length - 1) showImage(index +
                                    1);
                            });
                            $('.swal2-prev').on('click', () => {
                                if (index > 0) showImage(index - 1);
                            });
                        }
                    });
                };

                showImage(currentIndex);
            });
        });
    </script>


    <style>
        /* Make table rows appear clickable */

        .room-details {
            text-align: left;
            padding: 1rem;
        }

        .room-details p.facilities-row {
            align-items: flex-start;
        }

        .room-details p.facilities-row span {
            text-align: right;
            word-wrap: break-word;
            max-width: 60%;
        }

        .room-details p {
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 2rem;
            padding: 0.25rem 0;
        }

        .room-details p strong {
            color: #555;
            min-width: 140px;
            flex-shrink: 0;
        }

        .room-details p .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .room-details p .badge-success,
        .room-details p .bg-success-light {
            background-color: #e8f5e9;
            color: #28a745;
        }

        .room-details p .badge-danger,
        .room-details p .bg-danger-light {
            background-color: #ffebee;
            color: #dc3545;
        }

        /* Consistent modal styling */
        .swal2-popup {
            padding: 1.5rem !important;
        }

        .swal2-title {
            font-size: 1.5rem !important;
            margin-bottom: 1.5rem !important;
            padding: 0 !important;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 0 1rem !important;
        }

        .room-details .btn {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            display: inline-block;
        }

        /* Match status button styling */
        .room-details .bg-success-light {
            background-color: #e8f5e9;
            color: #28a745;
        }

        .room-details .bg-danger-light {
            background-color: #ffebee;
            color: #dc3545;
        }

        .datatable tbody tr {
            cursor: pointer;
        }

        /* Keep default cursor for carousel and action buttons */
        .datatable tbody tr .room-carousel,
        .datatable tbody tr .dropdown-action {
            cursor: default;
        }

        .datatable thead th {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .datatable tbody td {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Keep right alignment for action column */
        .datatable th.text-right,
        .datatable td.text-right {
            text-align: right !important;
        }

        .room-carousel {
            width: 150px;
            margin: 0 auto;
        }

        .room-carousel .carousel-item img {
            border-radius: 4px;
            width: 100%;
            height: 100px;
            object-fit: cover;
        }

        .room-carousel .carousel-control-prev,
        .room-carousel .carousel-control-next {
            width: 25px;
            height: 25px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }

        .room-carousel .carousel-control-prev {
            left: 5px;
        }

        .room-carousel .carousel-control-next {
            right: 5px;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 15px;
            height: 15px;
        }

        .badge {
            font-size: 11px;
            padding: 5px 8px;
            margin: 2px;
        }

        .table td {
            vertical-align: middle;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-secondary {
            background-color: #6c757d;
            cursor: pointer;
        }

        .image-viewer-popup {
            max-width: 90vw !important;
            padding: 1rem !important;
            position: relative;
        }

        .image-viewer-popup .swal2-image-container {
            position: relative;
            width: 100%;
        }

        .image-viewer-popup .swal2-image {
            max-height: 80vh !important;
            object-fit: contain;
            margin: 0 auto;
            display: block;
        }

        .swal2-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.6);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .swal2-nav:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .swal2-prev {
            left: 20px;
        }

        .swal2-next {
            right: 20px;
        }

        .room-carousel img {
            cursor: zoom-in !important;
        }
    </style>
@endsection
@endsection
