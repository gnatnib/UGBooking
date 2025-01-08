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
                                            <option selected value="{{ $roomEdit->room_type }}">{{ $roomEdit->room_type }}
                                            </option>
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
                                        id="capacity" name="capacity" value="{{ $roomEdit->capacity }}" min="1"
                                        max="50">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Photo</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('fileupload') is-invalid @enderror"
                                            id="fileupload" name="fileupload">
                                        <input type="hidden" name="hidden_fileupload" value="{{ $roomEdit->fileupload }}">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    @if ($roomEdit->fileupload)
                                        <div class="mt-2">
                                            <small>Room Photo:</small><br>
                                            <img src="{{ URL::to('/assets/upload/' . $roomEdit->fileupload) }}"
                                                alt="Room Photo" class="img-thumbnail" style="max-width: 150px">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="Ready" {{ $roomEdit->status === 'Ready' ? 'selected' : '' }}>Ready
                                        </option>
                                        <option value="Maintenance"
                                            {{ $roomEdit->status === 'Maintenance' ? 'selected' : '' }}>Maintenance
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Facilities</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="has_projector"
                                            name="has_projector" value="1"
                                            {{ $roomEdit->has_projector ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_projector">LCD Projector</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="has_sound_system"
                                            name="has_sound_system" value="1"
                                            {{ $roomEdit->has_sound_system ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_sound_system">Sound System</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="has_tv" name="has_tv"
                                            value="1" {{ $roomEdit->has_tv ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_tv">TV</label>
                                    </div>
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
            // Custom file input label
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        </script>
    @endpush
@endsection
