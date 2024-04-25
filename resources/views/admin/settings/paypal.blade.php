@extends('global.main')
@section('title',"PayPal Settings")
@section('description',"PayPal Settings")
@section('innerTitle',"PayPal Settings")
@section('breadcrumb',"")

@section('extra_css_files')
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">'        <!-- Bootstrap Date Range Picker Dependencies -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">

@endsection


@section('content')
    @if(session('success'))
        {!! alert_success(session('success')) !!}
    @elseif(session('error'))
        {!! alert_error(session('error')) !!}
    @endif
    <form role="form" action="" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>PayPal Mode</label>
            <select class="form-control" name="paypal_mode">
                <option {{ old('paypal_mode',$settings->paypal_mode) == "LIVE" ? 'selected' : '' }} value="LIVE">LIVE</option>
                <option {{ old('paypal_mode',$settings->paypal_mode) == "SANDBOX" ? 'selected' : '' }} value="SANDBOX">SANDBOX</option>
            </select>
        </div>
    <!--
        <div class="form-group">
            <label>PayPal Status</label>
            <select class="form-control" name="paypal_status">
                <option {{ old('paypal_status',$settings->paypal_status) == "OFF" ? 'selected' : '' }} value="OFF">OFF</option>
                <option {{ old('paypal_status',$settings->paypal_status) == "ON" ? 'selected' : '' }} value="ON">ON</option>
            </select>
        </div>
        -->
        <div class="form-group">
            <label>PayPal Client ID</label>
            <input value="{{ old('paypal_client_id',$settings->paypal_client_id) }}" id="paypal_client_id" value="" name="paypal_client_id" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>PayPal Client Secret</label>
            <input id="paypal_client_secret" value="{{ old('paypal_client_secret',$settings->paypal_client_secret) }}" name="paypal_client_secret" type="text" class="form-control">
        </div>
<!--
        <div class="form-group">
            <label>PayPal Account Seller Email</label>
            <input  value="{{ old('paypal_seller_email',$settings->paypal_seller_email) }}" name="paypal_seller_email" type="text" class="form-control">
        </div>
--->

        <button type="submit"  id="save" class="btn btn-success btn-sm">SAVE CHANGES</button>
    </form>
@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>

    <script src="{{ asset('js/init-timepicker.js') }}"></script>
    <script src="{{ asset('js/init-switchery.js') }}"></script>
    <script src="{{ asset('js/init-daterangepicker.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection
