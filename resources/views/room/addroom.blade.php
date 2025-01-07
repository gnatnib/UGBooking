@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Add Meeting Room</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('form/room/save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Type</label>
                                    <div class="input-group">
                                        <select class="form-control @error('room_type') is-invalid @enderror" id="room_type"
                                            name="room_type">
                                            <option selected disabled> --Select Room Type-- </option>
                                            @foreach ($data as $items)
                                                <option value="{{ $items->room_name }}">{{ $items->room_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" onclick="openAddRoomTypeModal()">
                                                Add New Type
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                onclick="openDeleteRoomTypeModal()">
                                                Delete Type
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Capacity</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                        id="capacity" name="capacity" min="1" max="50">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Photo</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('fileupload') is-invalid @enderror"
                                            id="fileupload" name="fileupload">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Facilities</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="has_projector"
                                            name="has_projector" value="1">
                                        <label class="form-check-label" for="has_projector">LCD Projector</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="has_sound_system"
                                            name="has_sound_system" value="1">
                                        <label class="form-check-label" for="has_sound_system">Sound System</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="has_tv" name="has_tv"
                                            value="1">
                                        <label class="form-check-label" for="has_tv">TV</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary buttonedit ml-2">Save</button>
                <button type="button" class="btn btn-secondary buttonedit">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add Room Type Modal -->
    <div class="modal fade" id="addRoomTypeModal" tabindex="-1" role="dialog" aria-labelledby="addRoomTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomTypeModalLabel">Add New Room Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_room_type">Room Type Name</label>
                        <input type="text" class="form-control" id="new_room_type" name="new_room_type">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRoomType">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Room Type Modal -->
    <div class="modal fade" id="deleteRoomTypeModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteRoomTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoomTypeModalLabel">Delete Room Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="delete_room_type">Select Room Type to Delete</label>
                        <select class="form-control" id="delete_room_type" name="delete_room_type">
                            <option selected disabled>--Select Room Type--</option>
                            @foreach ($data as $items)
                                <option value="{{ $items->id }}">{{ $items->room_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        Warning: Deleting a room type cannot be undone!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteRoomType">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openAddRoomTypeModal() {
                $('#addRoomTypeModal').modal('show');
            }

            function openDeleteRoomTypeModal() {
                $('#deleteRoomTypeModal').modal('show');
            }

            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#saveRoomType').click(function() {
                    const newRoomType = $('#new_room_type').val();

                    if (!newRoomType) {
                        alert('Please enter a room type name');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('room.type.add') }}",
                        type: "POST",
                        data: {
                            new_room_type: newRoomType
                        },
                        success: function(response) {
                            if (response.success) {
                                // Add the new option to select
                                $('#room_type').append(new Option(newRoomType, newRoomType));
                                $('#delete_room_type').append(new Option(newRoomType, response.id));

                                // Close modal and clear input
                                $('#addRoomTypeModal').modal('hide');
                                $('#new_room_type').val('');

                                // Show success message
                                alert('Room type added successfully!');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Failed to add room type. Please try again.');
                        }
                    });
                });

                $('#deleteRoomType').click(function() {
                    const roomTypeId = $('#delete_room_type').val();
                    const roomTypeName = $('#delete_room_type option:selected').text();

                    if (!roomTypeId) {
                        alert('Please select a room type to delete');
                        return;
                    }

                    if (confirm(`Are you sure you want to delete room type "${roomTypeName}"?`)) {
                        $.ajax({
                            url: "{{ route('room.type.delete') }}",
                            type: "POST",
                            data: {
                                room_type_id: roomTypeId
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove from both select elements
                                    $(`#room_type option[value="${roomTypeName}"]`).remove();
                                    $(`#delete_room_type option[value="${roomTypeId}"]`).remove();

                                    // Close modal
                                    $('#deleteRoomTypeModal').modal('hide');

                                    // Show success message
                                    alert('Room type deleted successfully!');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                alert(
                                    'Failed to delete room type. It might be in use by existing rooms.');
                            }
                        });
                    }
                });

                // Custom file input label
                $(".custom-file-input").on("change", function() {
                    var fileName = $(this).val().split("\\").pop();
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
            });
        </script>
    @endpush
@endsection
