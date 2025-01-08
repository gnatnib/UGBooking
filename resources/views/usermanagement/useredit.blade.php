@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Edit User</h3>
                    </div>
                </div>
            </div>

            {{-- Message --}}
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('users/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" name="user_id" value="{{ $userData->user_id }}" readonly>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Full Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        name="name" value="{{ $userData->name }}" placeholder="Enter Full Name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        name="email" value="{{ $userData->email }}" placeholder="Enter Email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                        name="phone_number" value="{{ $userData->phone_number }}" placeholder="Enter Phone Number">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role Name<span class="text-danger">*</span></label>
                                    <select class="form-control @error('role_name') is-invalid @enderror" name="role_name">
                                        <option selected disabled>Select Role Name</option>
                                        <option value="admin" {{ $userData->role_name == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ $userData->role_name == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                    @error('role_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Divisi<span class="text-danger">*</span></label>
                                    <select class="form-control @error('division') is-invalid @enderror" name="division">
                                        <option selected disabled>Select Divisi</option>
                                        <option value="Building Management" {{ $userData->division == 'Building Management' ? 'selected' : '' }}>Building Management</option>
                                        <option value="Construction and Property" {{ $userData->division == 'Construction and Property' ? 'selected' : '' }}>Construction and Property</option>
                                        <option value="IT Business and Solution" {{ $userData->division == 'IT Business and Solution' ? 'selected' : '' }}>IT Business and Solution</option>
                                        <option value="Finance and Accounting" {{ $userData->division == 'Finance and Accounting' ? 'selected' : '' }}>Finance and Accounting</option>
                                        <option value="Human Capital and General Affair" {{ $userData->division == 'Human Capital and General Affair' ? 'selected' : '' }}>Human Capital and General Affair</option>
                                        <option value="Risk, System, and Compliance" {{ $userData->division == 'Risk, System, and Compliance' ? 'selected' : '' }}>Risk, System, and Compliance</option>
                                        <option value="Internal Audit" {{ $userData->division == 'Internal Audit' ? 'selected' : '' }}>Internal Audit</option>
                                    </select>
                                    @error('division')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Department<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                        name="department" value="{{ $userData->department }}" placeholder="Enter Department">
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        name="password" placeholder="Enter Password">
                                    <small class="text-muted">Leave blank if you don't want to change password</small>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                        name="password_confirmation" placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('profile') is-invalid @enderror" 
                                            id="customFile" name="profile">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                        @error('profile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    @if($userData->avatar)
                                        <div class="mt-2">
                                            <small class="text-muted">Current image: {{ $userData->avatar }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('user/list') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <script>
        // Update custom file label with selected filename
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Auto-dismiss alerts
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    @endsection
@endsection