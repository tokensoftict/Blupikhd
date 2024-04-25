@extends('global.main')
@section('title',"Stripe Settings")
@section('description',"Stripe Settings")
@section('innerTitle',"Stripe Settings")
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
        <div class="form-group" style="display: none">
            <label>Stripe Status</label>
            <select class="form-control" name="stripe_status">
                <option {{ old('stripe_status',$settings->stripe_status) == "OFF" ? 'selected' : '' }} value="OFF">OFF</option>
                <option {{ old('stripe_status',$settings->stripe_status) == "ON" ? 'selected' : '' }} value="ON">ON</option>
            </select>
        </div>

        <div class="form-group">
            <label>Stripe Secret Key</label>
            <input  value="{{ old('stripe_api_key',$settings->stripe_api_key) }}" name="stripe_api_key" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>Stripe Publishable key</label>
            <input value="{{ old('stripe_public_key',$settings->stripe_public_key) }}" id="stripe_public_key"  name="stripe_public_key" type="text" class="form-control">
        </div>

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
