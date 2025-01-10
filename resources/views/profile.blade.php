@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header mt-5">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Profile</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="#">
                                    @if (Auth::user()->avatar)
                                        <img class="rounded-circle" alt="User Image"
                                            src="{{ asset('uploads/avatar/' . Auth::user()->avatar) }}">
                                    @else
                                        <img class="rounded-circle" alt="User Image"
                                            src="{{ asset('assets/img/profiles/avatar-11.jpg') }}">
                                    @endif
                                </a>
                            </div>
                            <div class="col ml-md-n2 profile-user-info">
                                <h4 class="user-name mb-3">{{ Auth::user()->name }}</h4>
                                <h6 class="text-muted mt-1">{{ ucfirst(Auth::user()->division) }}</h6>
                                <div class="user-Location mt-1"><i class="fas fa-building"></i>
                                    {{ Auth::user()->department }}</div>
                                <div class="about-text">{{ Auth::user()->role_name === 'admin' ? 'Administrator' : 'User' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-menu">
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item"> <a class="nav-link active" data-toggle="tab"
                                    href="#per_details_tab">About</a> </li>
                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password_tab">Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content profile-tab-cont">
                        <div class="tab-pane fade show active" id="per_details_tab">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title d-flex justify-content-between">
                                                <span>Personal Details</span>
                                                <a class="edit-link" data-toggle="modal" href="#edit_personal_details">
                                                    <i class="fa fa-edit mr-1"></i>Edit
                                                </a>
                                            </h5>
                                            <div class="row mt-5">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Name:</p>
                                                <p class="col-sm-9">{{ Auth::user()->name }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Employee ID:</p>
                                                <p class="col-sm-9">{{ Auth::user()->user_id }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Join Date:</p>
                                                <p class="col-sm-9">{{ Auth::user()->join_date }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Email ID:</p>
                                                <p class="col-sm-9">{{ Auth::user()->email }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Phone Number:</p>
                                                <p class="col-sm-9">{{ Auth::user()->phone_number }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Division:</p>
                                                <p class="col-sm-9">{{ Auth::user()->division }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0">Department:</p>
                                                <p class="col-sm-9 mb-0">{{ Auth::user()->department }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="password_tab" class="tab-pane fade">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Change Password</h5>
                                    <div class="row">
                                        <div class="col-md-10 col-lg-6">
                                            <form action="{{ route('update.password') }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Old Password</label>
                                                    <input type="password" name="current_password"
                                                        class="form-control @error('current_password') is-invalid @enderror">
                                                    @error('current_password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>New Password</label>
                                                    <input type="password" name="new_password"
                                                        class="form-control @error('new_password') is-invalid @enderror">
                                                    @error('new_password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Confirm Password</label>
                                                    <input type="password" name="new_password_confirmation"
                                                        class="form-control">
                                                </div>
                                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Personal Details Modal -->
    <div id="edit_personal_details" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Personal Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="row form-row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ Auth::user()->name }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ Auth::user()->email }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control"
                                        value="{{ Auth::user()->phone_number }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Division</label>
                                    <select class="form-control @error('division') is-invalid @enderror" name="division">
                                        <option selected disabled>Select Division</option>
                                        <option value="Building Management"
                                            {{ Auth::user()->division == 'Building Management' ? 'selected' : '' }}>
                                            Building Management</option>
                                        <option value="Construction and Property"
                                            {{ Auth::user()->division == 'Construction and Property' ? 'selected' : '' }}>
                                            Construction and Property</option>
                                        <option value="IT Business and Solution"
                                            {{ Auth::user()->division == 'IT Business and Solution' ? 'selected' : '' }}>IT
                                            Business and Solution</option>
                                        <option value="Finance and Accounting"
                                            {{ Auth::user()->division == 'Finance and Accounting' ? 'selected' : '' }}>
                                            Finance and Accounting</option>
                                        <option value="Human Capital and General Affair"
                                            {{ Auth::user()->division == 'Human Capital and General Affair' ? 'selected' : '' }}>
                                            Human Capital and General Affair</option>
                                        <option value="Risk, System, and Compliance"
                                            {{ Auth::user()->division == 'Risk, System, and Compliance' ? 'selected' : '' }}>
                                            Risk, System, and Compliance</option>
                                        <option value="Internal Audit"
                                            {{ Auth::user()->division == 'Internal Audit' ? 'selected' : '' }}>Internal
                                            Audit</option>
                                    </select>
                                    @error('division')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Department</label>
                                    <input type="text" name="department" class="form-control"
                                        value="{{ Auth::user()->department }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
