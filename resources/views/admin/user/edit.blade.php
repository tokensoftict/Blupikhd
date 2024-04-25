@extends('global.main')
@section('title',$page_title)
@section('description',$page_title)
@section('innerTitle',$page_title)
@section('breadcrumb',"")

@section('extra_css_files')
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    @if(session('success'))
        {!! alert_success(session('success')) !!}
    @elseif(session('error'))
        {!! alert_error(session('error')) !!}
    @endif

    <form role="form" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group">
            <label>First name</label>
            <input type="text" value="{{ old("firstname", $user->firstname) }}" required name="firstname" class="form-control" placeholder="First Name" />
        </div>

        <div class="form-group">
            <label>Last name</label>
            <input type="text" required value="{{ old("lastname", $user->lastname) }}" name="lastname" class="form-control" placeholder="Last Name" />
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" required value="{{ old("username", $user->username) }}" name="username" class="form-control" placeholder="Username" />
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" value="{{ old("email", $user->email) }}" required name="email" class="form-control" placeholder="Last Name" />
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" value="{{ old("phoneno", $user->phoneno) }}" required name="phoneno" class="form-control" placeholder="Phone Number" />
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="text"  name="password" class="form-control" placeholder="Password" />
            <small style="color: red;margin-top: -34px;display: block;">Please leave blank, if you dont want to change password</small>
        </div>
        <button type="submit"  id="save" class="btn btn-success btn-lg">Save Changes</button>
    </form>

@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection
