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
                            <!-- Ganti bagian Room Image dengan multiple upload -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Images</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('room_images') is-invalid @enderror" 
                                            id="room_images" name="room_images[]" multiple accept="image/*">
                                        <label class="custom-file-label" for="room_images">Choose files</label>
                                    </div>
                                    <small class="form-text text-muted">You can select multiple images</small>
                                    <div id="image_preview" class="mt-2 row"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room Facilities</label>
                                    <div id="facilities_container">
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
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-2" id="add_facility">
                                        <i class="fas fa-plus"></i> Add Facility
                                    </button>
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
@endsection

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
        files.length > 1 ? `${files.length} files selected` : files[0].name
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

// Modal functions
window.openAddRoomTypeModal = function() {
    $('#addRoomTypeModal').modal('show');
};

window.openDeleteRoomTypeModal = function() {
    $('#deleteRoomTypeModal').modal('show');
};

// Setup AJAX CSRF token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Handle save room type
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
                $('#room_type').append(new Option(newRoomType, newRoomType));
                $('#delete_room_type').append(new Option(newRoomType, response.id));
                $('#addRoomTypeModal').modal('hide');
                $('#new_room_type').val('');
                alert('Room type added successfully!');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Failed to add room type. Please try again.');
        }
    });
});

// Handle delete room type
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
                    $(`#room_type option[value="${roomTypeName}"]`).remove();
                    $(`#delete_room_type option[value="${roomTypeId}"]`).remove();
                    $('#deleteRoomTypeModal').modal('hide');
                    alert('Room type deleted successfully!');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Failed to delete room type. It might be in use by existing rooms.');
            }
        });
    }
});
});
</script>
@endpush