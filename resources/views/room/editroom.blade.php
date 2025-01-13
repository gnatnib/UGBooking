
@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit Meeting Room</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('form/room/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <input class="form-control" type="hidden" name="bkg_room_id" 
                                value="{{ $roomEdit->bkg_room_id }}" readonly>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <div class="input-group">
                                        <select class="form-control @error('room_type') is-invalid @enderror" id="room_type"
                                            name="room_type">
                                            <option selected value="{{ $roomEdit->room_type }}">{{ $roomEdit->room_type }}</option>
                                            @foreach ($data as $items)
                                                <option value="{{ $items->room_name }}">{{ $items->room_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Capacity</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                        id="capacity" name="capacity" value="{{ $roomEdit->capacity }}" min="1" max="50">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Images</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('room_images') is-invalid @enderror" 
                                            id="room_images" name="room_images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="room_images">Choose files</label>
                                    </div>
                                    <small class="form-text text-muted">You can select multiple images</small>
                                    
                                    <!-- Current Images Preview -->
                                    <div id="current_images" class="mt-2 row">
                                        @if ($roomEdit->images)
                                            @foreach(json_decode($roomEdit->images) as $index => $image)
                                                <div class="col-md-4 mb-2">
                                                    <div class="position-relative">
                                                        <img src="{{ asset('uploads/rooms/' . $image) }}"
                                                            alt="Room Photo" class="img-thumbnail" 
                                                            style="height: 150px; object-fit: cover;">
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute"
                                                                style="top: 5px; right: 20px;"
                                                                onclick="removeImage(this, '{{ $image }}')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    <!-- New Images Preview -->
                                    <div id="image_preview" class="mt-2 row"></div>
                                    <input type="hidden" name="removed_images" id="removed_images">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="Ready" {{ $roomEdit->status === 'Ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="Maintenance" {{ $roomEdit->status === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Facilities</label>
                                    <div id="facilities_container">
                                        @if ($roomEdit->facilities)
                                            @foreach(json_decode($roomEdit->facilities) as $facility)
                                                <div class="facility-item mb-2">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="facilities[]" 
                                                               value="{{ $facility }}" placeholder="Enter facility name">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-danger remove-facility">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="facility-item mb-2">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="facilities[]" 
                                                           placeholder="Enter facility name">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-danger remove-facility">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-2" id="add_facility">
                                        <i class="fas fa-plus"></i> Add Facility
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary buttonedit">Update</button>
                <a href="{{ route('form/allrooms/page') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Handle multiple image upload
            $('#room_images').on('change', function() {
                const files = Array.from(this.files);
                const preview = $('#image_preview');
                preview.empty();

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.append(`
                            <div class="col-md-4 mb-2">
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="img-thumbnail" style="height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                            style="top: 5px; right: 20px;"
                                            onclick="$(this).closest('.col-md-4').remove();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);
                });

                // Update file input label
                $(this).siblings('.custom-file-label').html(
                    files.length > 1 ? ${files.length} files selected : files[0].name
                );
            });

            // Handle dynamic facilities
            $('#add_facility').click(function() {
                $('#facilities_container').append(`
                    <div class="facility-item mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="facilities[]" placeholder="Enter facility name">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-facility">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            });

            // Remove facility
            $(document).on('click', '.remove-facility', function() {
                $(this).closest('.facility-item').remove();
            });
        });

        // Array to store removed images
        let removedImages = [];

        // Function to remove existing image
        function removeImage(button, imageName) {
            $(button).closest('.col-md-4').remove();
            removedImages.push(imageName);
            $('#removed_images').val(JSON.stringify(removedImages));
        }
    </script>
    @endpush
@endsection