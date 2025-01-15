@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left mt-2">All List Users</h4>
                            <a href="{{ route('users/add/new') }}" class="btn btn-primary float-right veiwbutton">Add User</a> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="table table-stripped table table-hover table-center mb-0" id="UsersList">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Division</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Modify</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('script')
    {{-- Tambahkan SweetAlert2 CSS dan JS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
       $(document).ready(function() {
    var table = $('#UsersList').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            url:"{{ route('get-users-data') }}",
        },
        columns: [
            {
                data: 'user_id',
                name: 'user_id',
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'phone_number',
                name: 'phone_number'
            },
            {
                data: 'division',
                name: 'division'
            },
            {
                data: 'department',
                name: 'department'
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="dropdown dropdown-action text-center">
                            <a href="" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v ellipse_color"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="/users/add/edit/${row.user_id}">
                                    <i class="fas fa-pencil-alt m-r-5"></i> Edit
                                </a>
                                <a class="dropdown-item delete-user" href="javascript:void(0);" data-id="${row.id}">
                                    <i class="fas fa-trash-alt m-r-5"></i> Delete
                                </a>
                            </div>
                        </div>
                    `;
                }
            },
        ]
    });

    // Handler untuk delete button
    $(document).on('click', '.delete-user', function() {
        var userId = $(this).data('id');
        console.log('Deleting user with ID:', userId); // Untuk debugging
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/users/delete/${userId}`;
            }
        });
    });
});
    </script>
    @endsection
@endsection