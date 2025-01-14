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
                                            <th>Booking ID</th>
                                            <th>Room Type</th>
                                            <th>Capacity</th>
                                            <th>Images</th>
                                            <th>Facilities</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allRooms as $rooms)
                                            <tr>
                                                <td hidden class="id">{{ $rooms->id }}</td>
                                                <td>{{ $rooms->bkg_room_id }}</td>
                                                <td>{{ $rooms->room_type }}</td>
                                                <td>{{ $rooms->capacity }}</td>
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
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v ellipse_color"></i>
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
            // Event listener untuk baris tabel (kecuali kolom gambar dan aksi)
            $('.datatable tbody').on('click', 'tr', function(e) {
                // Pastikan kolom gambar dan aksi tidak memicu event
                if ($(e.target).closest('.room-carousel, .dropdown-action').length) {
                    return;
                }

                // Ambil semua fasilitas dari data attribute
                const allFacilities = $(this).find('.facility-badge:first')
                    .data('facilities')
                    .split(', ')
                    .join(', ');

                // Ambil status dengan cara yang benar
                const statusBtn = $(this).find('td .actions .btn').first();
                const status = statusBtn.text();
                const statusClass = statusBtn.hasClass('bg-success-light') ? 'bg-success-light' :
                    'bg-danger-light';

                const roomDetails = {
                    id: $(this).find('.id').text().trim(),
                    roomType: $(this).find('td:nth-child(3)').text().trim(),
                    capacity: $(this).find('td:nth-child(4)').text().trim(),
                    facilities: allFacilities,
                    status: status,
                    statusClass: statusClass
                };

                Swal.fire({
                    title: `Detail Ruangan - ${roomDetails.roomType}`,
                    html: `
                <div class="room-details">
                    <p><strong>Tipe Ruangan:</strong> ${roomDetails.roomType}</p>
                    <p><strong>Kapasitas:</strong> ${roomDetails.capacity} orang</p>
                    <p><strong>Fasilitas:</strong> ${roomDetails.facilities || 'Tidak ada fasilitas'}</p>
                    <p><strong>Status:</strong> <span class="btn btn-sm ${roomDetails.statusClass} mr-2">${roomDetails.status}</span></p>
                </div>
            `,
                    confirmButtonText: 'Tutup',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            });
        });
    </script>


    <style>
        /* Make table rows appear clickable */
        .datatable tbody tr {
            cursor: pointer;
        }

        /* Keep default cursor for carousel and action buttons */
        .datatable tbody tr .room-carousel,
        .datatable tbody tr .dropdown-action {
            cursor: default;
        }

        .room-carousel {
            width: 150px;
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
    </style>
@endsection
@endsection
