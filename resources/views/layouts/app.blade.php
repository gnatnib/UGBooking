<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>UG Booking</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::to('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/feathericon.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">
    <style>
        body {
            background-image: url("{{ URL::to('assets/img/backgroundug.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            /* Remove any existing opacity on the body */
            opacity: 1;
        }

        .login-wrapper {
            /* Adjust the white background opacity of the login wrapper */
            background: rgba(255, 255, 255, 0.85);
            /* Changed from 0.95 to 0.85 */
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            /* Enhanced shadow */
            margin: 20px;
        }

        .loginbox {
            display: flex;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
        }

        .login-left {
            background: #ffffff;
            padding: 30px;
            width: 40%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-right {
            padding: 30px;
            width: 60%;
            background: #ffffff;
            /* Ensure solid white background */
        }

        /* Add a dark overlay to make background image more visible */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            /* Slight dark overlay */
            z-index: -1;
        }

        /* Rest of your existing styles... */
    </style>
</head>

<body>
    @yield('content')

    <script src="{{ URL::to('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/popper.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/script.js') }}"></script>
</body>

</html>
