<nav id="mobile-navigation-pro">
    <div class="menu-collapser" style="display: block;">Menu<div class="collapse-button"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></div></div><ul id="mobile-menu-pro" class="collapsed" style="display: none;">
        <li class="current-menu-item">
            <a href="{{ route('user.dashboard') }}">
                <span class="icon-Old-TV"></span>
               Watch Now
            </a>
        </li><li>
        </li><li>
            <a href="{{ route('user.profile') }}">
                <span class="icon-Add-User"></span>
                My Profile
            </a>
        </li>
        <li>
            <a href="{{ route('user.wallet') }}">
                <span class="icon-Wallet"></span>
                My Wallet
            </a>
        </li>
        <li>
            <a href="{{ route('user.movie_request') }}">
                <span class="icon-Movie"></span>
                Movie Request
            </a>
        </li>
        <li>
            <a href="{{ route('blogs') }}" target="_blank">
                <span class="icon-Newspaper-2"></span>
                News
            </a>
        </li>
        <li>
            <a href="{{ route('registration.logout') }}">
                <span class="icon-User"></span>
                Log Out
            </a>
        </li>
    </ul>
    <div class="clearfix"></div>
</nav>
