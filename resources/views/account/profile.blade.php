@extends('account.shared.main')
@section('breadcrumb',"")
@section('content')
    <div class="dashboard-container">

        <ul class="dashboard-sub-menu">
            <li class="current"><a href="{{ route('user.profile') }}">Account Settings</a></li>
            <li><a href="{{ route('user.wallet') }}">My Wallet</a></li>
        </ul><!-- close .dashboard-sub-menu -->

        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    @if(session('success'))
                        {!! alert_success(session('success')) !!}
                    @elseif(session('error'))
                        {!! alert_error(session('error')) !!}
                    @endif
                    <form class="account-settings-form" action="" method="post">
                        {{ csrf_field() }}
                        <h5>General Information</h5>
                        <p class="small-paragraph-spacing">By letting us know your name, we can make our support experience much more personal.</p>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="first-name" class="col-form-label">First Name:</label>
                                    <input type="text" class="form-control" id="first-name" name="firstname" value="{{ \App\User::find(auth('frontenduser')->id())->firstname }}">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="last-name" class="col-form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="last-name" name="lastname" value="{{ \App\User::find(auth('frontenduser')->id())->lastname }}">
                                </div>
                            </div><!-- close .col -->
                        </div><!-- close .row -->
                        <hr>

                        <h5>Account Information</h5>
                        <p class="small-paragraph-spacing">You can change the email address you use here.</p>

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="e-mail" class="col-form-label">E-mail</label>
                                    <input type="text" class="form-control" name="email" disabled id="e-mail" value="{{ \App\User::find(auth('frontenduser')->id())->email }}">
                                </div>
                            </div><!-- close .col -->
                            <div class="col-sm">
                                <div class="form-group">
                                    <div><label for="button-change" class="col-form-label">&nbsp; &nbsp;</label></div>
                                    <button type="button" onclick="return $('#e-mail').removeAttr('disabled');" class="btn btn-form">Change E-mail</button>
                                </div>
                            </div><!-- close .col -->

                        </div><!-- close .row -->

                        <hr>
                        <h5>Change Password</h5>
                        <p class="small-paragraph-spacing">You can change the password you use for your account here.</p>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="password" class="col-form-label">Current Password:</label>
                                    <input type="text" name="current_password" class="form-control" id="password" value="">
                                </div>
                            </div><!-- close .col -->
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="new-password" class="col-form-label">New Password:</label>
                                    <input type="text" name="password" class="form-control" id="new-password" placeholder="Minimum of 6 characters">
                                </div>
                            </div><!-- close .col -->
                            <div class="col-sm">
                                <div class="form-group">
                                    <div><label for="confirm-password" class="col-form-label">&nbsp; &nbsp;</label></div>
                                    <input type="text" name="confirm_password" class="form-control" id="confirm-password" placeholder="Confirm New Password">
                                </div>
                            </div><!-- close .col -->
                        </div><!-- close .row -->

                        <div class="clearfix"></div>
                        <hr>
                        <p><button type="submit"  class="btn btn-green-pro">Update Profile</button></p>
                        <br>
                    </form>

                </div><!-- close .col -->

            </div><!-- close .row -->
        </div><!-- close .container-fluid -->

    </div>
@endsection
