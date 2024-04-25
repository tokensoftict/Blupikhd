<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('frontend') }}/images/tv_logo.png" />
    <link rel="stylesheet" href="{{ asset('frontend') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/style.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:400,700%7CMontserrat:300,400,600,700">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('frontend') }}/icons/fontawesome/css/fontawesome-all.min.css"><!-- FontAwesome Icons -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/icons/Iconsmind__Ultimate_Pack/Line%20icons/styles.min.css"><!-- iconsmind.com Icons -->
     @yield('css')
    <title>{{ $page_title }}</title>
</head>
<body>
<div id="sidebar-bg">
    <header id="videohead-pro" class="sticky-header">
        <div id="video-logo-background"><a href="{{ route('user.dashboard') }}"><img src="{{ asset('frontend') }}/images/tv_logo.png" alt="Logo" height="80"></a></div>
        <div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
        <div id="header-user-profile">
            <div id="header-user-profile-click" class="noselect">
                <img src="{{ asset('frontend') }}/images/1.png" alt="{{ auth('frontenduser')->user()->firstname }} {{ auth('frontenduser')->user()->lastname }}">
                <div id="header-username">{{ auth('frontenduser')->user()->firstname }} {{ auth('frontenduser')->user()->lastname }}</div><i class="fas fa-angle-down"></i>
            </div>
            <div id="header-user-profile-menu">
                <ul>
                    <li><a href="{{ route('user.profile') }}"><span class="icon-User"></span>My Profile</a></li>
                    <li><a href="{{ route('user.wallet') }}"><span class="icon-Gears"></span>My Wallet</a></li>
                    <li><a href="{{ route('user.movie_request') }}"><span class="icon-Movie"></span>Movie Request</a></li>
                    <li><a  target="_blank" href="{{ route('contact') }}"><span class="icon-Life-Safer"></span>Help/Support</a></li>
                    <li><a href="{{ route('registration.logout') }}"><span class="icon-Power-3"></span>Log Out</a></li>
                </ul>
            </div>
        </div>
        <div id="header-user-notification">
            <div id="header-user-notification-click" class="noselect">
                <i class="far fa-bell"></i>
                <!--  <span class="user-notification-count">3</span>-->
             </div><!-- close #header-user-profile-click -->
            <div id="header-user-notification-menu">
                <h3>Notifications</h3>
                <div id="header-notification-menu-padding">
                    <ul id="header-user-notification-list">
                        @foreach($notifications as $notification)
                        <li><a href="#!"><img src="{{asset('frontend')}}/images/1.png" alt="Profile">{{ $notification->body }} <div class="header-user-notify-time">{{ time_elapsed_string($notification->created_at) }}</div></a></li>
                        @endforeach
                    </ul>
                    <div class="clearfix"></div>
                </div><!-- close #header-user-profile-menu -->
            </div>
        </div>
        <div class="clearfix"></div>
        @include('account.shared.mobilelink')
    </header>

@include('account.shared.sidelink')
