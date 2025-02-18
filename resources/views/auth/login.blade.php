@extends('layouts.app')
@section('content')
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{ URL::to('assets/img/logoUG.png') }}" alt="Logo" style=" border-radius: 5px;">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Login</h1>
                            <p class="account-subtitle">Access to our dashboard</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control @error('user_id') is-invalid @enderror" type="text" name="user_id"
                                        placeholder="Enter User ID" value="{{ old('user_id') }}" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input class="form-control  @error('password') is-invalid @enderror" type="password" name="password" placeholder="Enter Password" value="{{ old('password') }}" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit">Login</button>
                                </div>
                                <a href="{{ route('publiccalendar') }}" class="btn btn-light-blue my-4 center">
                                    <i class="fas fa-calendar mr-2"></i> View Booking Schedule
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

