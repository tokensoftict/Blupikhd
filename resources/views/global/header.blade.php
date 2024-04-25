<header id="header" class="ui-header">

    <div class="navbar-header">
        <!--logo start-->
        <a href="/" class="navbar-brand">
            <span class="logo"><img src="{{ asset('logo.png') }}" alt="" style="width:72%;"></span>
            <span class="logo-compact"><img src="{{ asset('') }}img/logo-icon-dark.png" alt=""></span>
        </a>
        <!--logo end-->
    </div>

    <div class="navbar-collapse nav-responsive-disabled">

        <!--toggle buttons start-->
        <ul class="nav navbar-nav">
            <li>
                <a class="toggle-btn" data-toggle="ui-nav" href="">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        </ul>
        <!-- toggle buttons end -->

        <!--notification start-->
        <ul class="nav navbar-nav navbar-right">


            <li class="dropdown dropdown-usermenu">
                <a href="#" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <div class="user-avatar"><img src="{{ asset('') }}imgs/a0.jpg" alt="..."></div>
                    <span class="hidden-sm hidden-xs">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span>
                    <span class="caret hidden-sm hidden-xs"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
        <!--notification end-->
    </div>
</header>
