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
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="#">
                                    @if (Auth::user()->avatar)
                                        <img class="rounded-circle" alt="User Image"
                                            src="{{ URL::to('assets/img/profiles/' . Auth::user()->avatar) }}">
                                    @else
                                        <img class="rounded-circle" alt="User Image"
                                            src="{{ URL::to('assets/img/profiles/avatar-11.jpg') }}">
                                    @endif
                                </a>
                            </div>
                            <div class="col ml-md-n2 profile-user-info">
                                <h4 class="user-name mb-3">{{ Auth::user()->name }}</h4>
                                <h6 class="text-muted mt-1">{{ ucfirst(Auth::user()->position) }}</h6>
                                <div class="user-Location mt-1"><i class="fas fa-building"></i>
                                    {{ Auth::user()->department }}</div>
                                <div class="about-text">{{ Auth::user()->role_name === 'admin' ? 'Administrator' : 'User' }}
                                </div>
                            </div>
                            <div class="col-auto profile-btn">
                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                    data-target="#edit_personal_details">
                                    Edit
                                </a>
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
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Name</p>
                                                <p class="col-sm-9">{{ Auth::user()->name }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Employee ID</p>
                                                <p class="col-sm-9">{{ Auth::user()->user_id }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Join Date</p>
                                                <p class="col-sm-9">{{ Auth::user()->join_date }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Email ID</p>
                                                <p class="col-sm-9">{{ Auth::user()->email }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0 mb-sm-3">Mobile</p>
                                                <p class="col-sm-9">{{ Auth::user()->phone_number }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-3 text-sm-right mb-0">Department</p>
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
@endsection
