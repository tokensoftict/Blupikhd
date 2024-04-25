<aside id="aside" class="ui-aside">
    <ul class="nav" ui-nav>
        <li class="nav-head">
            <h5 class="nav-title text-uppercase light-txt">Navigation</h5>
        </li>
        <li>
            <a href=""><i class="fa fa-home"></i><span>Dashboard</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Dashboard</span></a></li>
                <li><a href="{{ route("dashboard") }}"><span>Dashboard</span></a></li></ul>
        </li>
        <li>
            <a href=""><i class="fa fa-users"></i><span>Subscribers</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Subscribers</span></a></li>
                <li><a href="{{ route('subscribers.lists') }}"><span>Subscribers</span></a></li>
                <li><a href="{{ route('subscribers.active') }}"><span>Active Subscribers</span></a></li>
                <li><a href="{{ route('subscribers.add_fund_to_wallet') }}"><span>Add Fund To Wallet</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-newspaper-o"></i><span>Blog</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Blog</span></a></li>
                <li><a href="{{ route('blog.lists') }}"><span>List Post</span></a></li>
                <li><a href="{{ route('blog.create') }}"><span>New Post</span></a></li>
                <li><a href="{{ route('category.lists') }}"><span>Category</span></a></li>
                <li><a href="{{ route('tag.lists') }}"><span>Tag</span></a></li>
               <li><a href="{{ route('blog.comments') }}"><span>Comments</span></a></li>
            </ul>
        </li>
        <li>
        <li>
            <a href=""><i class="fa fa-file-video-o"></i><span>Movies & Series Request</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Movies & Series Request</span></a></li>
                <li><a href="{{ route('subscribers.series_request') }}"><span>Series Request List</span></a></li>
                <li><a href="{{ route('subscribers.movies_request') }}"><span>Movies Request List</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-file-video-o"></i><span>AWS File Manager</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>AWS File Manager</span></a></li>
                <li><a href="{{ route('aws.list') }}"><span>List Bucket(s)</span></a></li>
                <li><a href="{{ route('aws.create') }}"><span>Create Bucket(s)</span></a></li>
                <li><a href="{{ route('aws.upload_form') }}"><span>Upload File</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-envelope"></i><span>Notification</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Notification</span></a></li>
                <li><a href="{{ route('subscribers.list_notification') }}"><span>List Notification</span></a></li>
                <li><a href="{{ route('subscribers.new_notification') }}"><span>New Notification</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-list-alt"></i><span>Transactions</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Transactions</span></a></li>
                <li><a href="{{ route('transaction.wallet') }}"><span>Wallet Transactions</span></a></li>
                <li><a href="{{ route('transaction.topup') }}"><span>Top Up Transaction</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-gears"></i><span>Plan Settings</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Plan Settings</span></a></li>
                <li><a href="{{ route('plan.lists') }}"><span>List Plan</span></a></li>
                <li><a href="{{ route('plan.new') }}"><span>Add New Plan</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-credit-card"></i><span>Payments Settings</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Payment Settings</span></a></li>
                <li><a href="{{ route('setting.paypal') }}"><span>PayPal Settings</span></a></li>
                <li><a href="{{ route('setting.googleplay') }}"><span>Google Play Settings</span></a></li>
                <li><a href="{{ route('setting.appleplay') }}"><span>Apple Play Settings</span></a></li>
                <li><a href="{{ route('setting.stripe') }}"><span>Stripe Settings</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-gear"></i><span>Settings</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Settings</span></a></li>
                <li><a href="{{ route('setting.access_mode') }}"><span>Bluipkhd Access Mode</span></a></li>
                <li><a href="{{ route('setting.schedule') }}"><span>Program Schedules</span></a></li>
                <li><a href="{{ route('setting.ads_mananger') }}"><span>Ads Manager</span></a></li>
                <li><a href="{{ route('setting.terms_condition') }}"><span>Terms and Condition</span></a></li>
                <li><a href="{{ route('setting.frontpagesettings') }}"><span>Front Page Settings</span></a></li>
            </ul>
        </li>
        <li>
            <a href=""><i class="fa fa-user"></i><span>Users</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href=""><span>Users</span></a></li>
                <li><a href="{{ route("users.lists") }}"><span>Lists</span></a></li>
                <li><a href="{{ route("users.add") }}"><span>Add</span></a></li>
                <li><a href="{{ route('logout') }}"><span>Log Out</span></a></li>
            </ul>
        </li>
    </ul>
</aside>
