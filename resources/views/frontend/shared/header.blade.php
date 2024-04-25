<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="facebook-domain-verification" content="571d5slqv8vsuz4ctix9t1rhma60ox" />
    @stack('blog_meta')
    <link rel="shortcut icon" href="{{ asset('frontend') }}/images/tv_logo.png" />
    <link rel="stylesheet" href="{{ asset('frontend') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/style.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:400,700%7CMontserrat:300,400,600,700">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('frontend') }}/icons/fontawesome/css/fontawesome-all.min.css"><!-- FontAwesome Icons -->
    <link rel="stylesheet" href="{{ asset('frontend') }}/icons/Iconsmind__Ultimate_Pack/Line%20icons/styles.min.css"><!-- iconsmind.com Icons -->
    @stack('extra_css')
    @yield('extra_css')
    <title>{{ $page_title }}</title>
</head>
<body>
<header id="masthead-pro">
    <div class="container-fluid">

        <h1 class="ml-lg-5 pl-lg-5 "><a href="{{ route('index') }}"><img src="{{ asset('frontend') }}/images/2.png" alt="Logo"></a></h1>
        <nav id="site-navigation-pro">
            <ul class="sf-menu">
                <li class="normal-item-pro {{ $active_link == "home" ? 'current-menu-item' : '' }}">
                    <a href="{{ config('app.url') }}">Home</a>
                </li>
                <li class="normal-item-pro  {{ $active_link == "blog" ? 'current-menu-item' : '' }}">
                    <a href="{{ route('blogs') }}">News</a>
                </li>
                <li class="normal-item-pro {{ $active_link == "faqs" ? 'current-menu-item' : '' }}">
                    <a href="{{ route('faqs') }}">Faqs</a>
                </li>
                <li class="normal-item-pro {{ $active_link == "partners" ? 'current-menu-item' : '' }}">
                    <a href="{{ route('partners') }}">Partners</a>
                </li>
                <li class="normal-item-pro {{ $active_link == "contact" ? 'current-menu-item' : '' }}">
                    <a href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </nav>

        @if(auth('frontenduser')->check())
            <a href="{{ route('user.dashboard') }}" class="btn btn-header-pro btn-green-pro noselect">My Account</a>
        @else
            <a href="{{ route('registration.index') }}" class="btn btn-header-pro btn-green-pro noselect mr-5">Sign Up</a>
            <button class="btn btn-header-pro noselect" data-toggle="modal" data-target="#LoginModal" role="button">Sign In</button>
        @endif
        <!--
        <a href="#" class="btn-header-pro noselect mr-5"><img src="{{ asset('frontend/images/google.png') }}" width="150"/> </a>
        <a href="#" class="btn-header-pro noselect mr-2"><img src="{{ asset('frontend/images/apple.png') }}" width="130"/> </a>
        -->



        <div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>

        <div class="clearfix"></div>
    </div>

    <nav id="mobile-navigation-pro">

        <ul id="mobile-menu-pro">
            <li>
                <a href="{{ config('app.url') }}">Home</a>
            </li>
            <li>
                <a href="#">Blog</a>
            </li>
            <li>
                <a href="#">Terms</a>
            </li>
            <li>
                <a href="#">Contact Us</a>
            </li>
        </ul>
        <div class="clearfix"></div>

        <button class="btn btn-mobile-pro btn-green-pro noselect" data-toggle="modal" data-target="#LoginModal" role="button">Sign In</button>

    </nav>
</header>
