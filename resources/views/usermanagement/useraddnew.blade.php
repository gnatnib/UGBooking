@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title mt-5">Add New User</h3>
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

            {{-- Form --}}
            <form action="{{ route('user/save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row formtype">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>User ID<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('user_id') is-invalid @enderror" 
                                        name="user_id" value="{{ old('user_id') }}" placeholder="Enter User ID (e.g., ITBS01)">
                                    @error('user_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Full Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        name="name" value="{{ old('name') }}" placeholder="Enter Full Name">
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
                                        name="email" value="{{ old('email') }}" placeholder="Enter Email">
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
                                        name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter Phone Number">
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
                                        <option value="admin" {{ old('role_name') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ old('role_name') == 'user' ? 'selected' : '' }}>User</option>
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
                                    <label>Division<span class="text-danger">*</span></label>
                                    <select class="form-control @error('division') is-invalid @enderror" name="division">
                                        <option selected disabled>Select Division</option>
                                        <option value="Building Management" {{ old('division') == 'Building Management' ? 'selected' : '' }}>Building Management</option>
                                        <option value="Construction and Property" {{ old('division') == 'Construction and Property' ? 'selected' : '' }}>Construction and Property</option>
                                        <option value="IT Business and Solution" {{ old('division') == 'IT Business and Solution' ? 'selected' : '' }}>IT Business and Solution</option>
                                        <option value="Finance and Accounting" {{ old('division') == 'Finance and Accounting' ? 'selected' : '' }}>Finance and Accounting</option>
                                        <option value="Human Capital and General Affair" {{ old('division') == 'Human Capital and General Affair' ? 'selected' : '' }}>Human Capital and General Affair</option>
                                        <option value="Risk, System, and Compliance" {{ old('division') == 'Risk, System, and Compliance' ? 'selected' : '' }}>Risk, System, and Compliance</option>
                                        <option value="Internal Audit" {{ old('division') == 'Internal Audit' ? 'selected' : '' }}>Internal Audit</option>
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
                                        name="department" value="{{ old('department') }}" placeholder="Enter Department">
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        name="password" placeholder="Enter Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Confirm Password<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                        name="password_confirmation" placeholder="Confirm Password">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="mb-2">Profile Image</label>
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
                                </div>

                                <div class="action-buttons mt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-create flex-grow-1">Create New User</button>
                                        <a href="{{ route('user/list') }}" class="btn btn-cancel flex-grow-1">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @section('script')
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.1);
        }

        .custom-file {
            position: relative;
            width: 100%;
        }

        .custom-file-label {
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            background-color: #fff;
            border: 1px solid #e2e8f0;
        }

        .custom-file-input:focus ~ .custom-file-label {
            border-color: #fbbf24;
            box-shadow: 0 0 0 2px rgba(251, 191, 36, 0.1);
        }

        .action-buttons {
            width: 100%;
        }

        .gap-2 {
            gap: 0.75rem;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            border: none;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .btn-create {
            background-color: #fbbf24;
            color: white;
        }

        .btn-create:hover {
            background-color: #f59e0b;
            color: white;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background-color: #ef4444;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #dc2626;
            color: white;
            transform: translateY(-1px);
        }

        .d-flex {
            display: flex;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        @media (max-width: 768px) {
            .action-buttons .d-flex {
                flex-direction: column;
            }
            
            .gap-2 {
                gap: 0.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
    </style>

    <script>
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    @endsection
@endsection