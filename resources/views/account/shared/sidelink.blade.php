<nav id="sidebar-nav"><!-- Add class="sticky-sidebar-js" for auto-height sidebar -->
    <ul id="vertical-sidebar-nav" class="sf-menu">
        <li class="normal-item-pro current-menu-item">
            <a href="{{ route('user.dashboard') }}">
                <span class="icon-Old-TV"></span>
                Watch Now
            </a>
        </li>
        <li class="normal-item-pro">
            <a href="{{ route('user.profile') }}">
                <span class="icon-Add-User"></span>
                My Profile
            </a>
        </li>
        <li class="normal-item-pro">
            <a href="{{ route('user.movie_request') }}">
                <span class="icon-Movie"></span>
                Movie Request
            </a>
        </li>
        <li class="normal-item-pro">
            <a href="{{ route('blogs') }}" target="_blank">
                <span class="icon-Newspaper-2"></span>
                News
            </a>
        </li>
        <li class="normal-item-pro">
            <a href="{{ route('registration.logout') }}">
                <span class="icon-User"></span>
                Log Out
            </a>
        </li>

    </ul>
    <div class="clearfix"></div>
</nav>
