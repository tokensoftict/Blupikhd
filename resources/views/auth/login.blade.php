<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" type="image/png" href="/imgs/favicon.png" /> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login {{ config('app.name', 'Blueikhd Backend') }}</title>

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/weather-icons/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/themify-icons/css/themify-icons.css') }}">
    <!-- endinject -->

    <!-- Main Style  -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <script src="{{ asset('js/modernizr-custom.js') }}"></script>
</head>
<body>

<div class="sign-in-wrapper">
    <div class="sign-container">
        <div class="text-center">
            <h2 class="logo"><img src="{{ asset('logo.png') }}" width="250px" alt=""/></h2>
            <h4>Welcome Please Login</h4>
        </div>
        @if(session('success'))
            {!! alert_success(session('success')) !!}
        @elseif(session('error'))
            {!! alert_error(session('error')) !!}
        @endif
        <form class="sign-in-form" role="form" action="{{ route('login_process') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Username" required="">
                @if ($errors->has('email'))
                    <span class="help-block" style="color:#f00;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                @endif
                @if ($errors->has('username'))
                    <span class="help-block" style="color:#f00;">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                @endif
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required="">
            </div>
            <button type="submit" class="btn btn-info btn-block">Login</button>
        </form>
        <div class="text-center copyright-txt">
            <small>{{ config('app.name', 'Blueikhd Backend') }} - Copyright © @php echo date('Y')  @endphp</small>
        </div>
    </div>
</div>

<!-- inject:js -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('bower_components/autosize/dist/autosize.min.js') }}"></script>
<!-- endinject -->

<!-- Common Script   -->
<script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
