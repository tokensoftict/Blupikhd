@extends('global.main')
@section('title',"Approve Movie Request")
@section('description',"Approve Movie Request")
@section('innerTitle',"Approve Movie Request")
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

    <form role="form"  method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" value="{{ old('title', $movies_request->title) }}" placeholder="Request / Movie Title" type="text" name="title">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <textarea style="height: 150px" name="comment" placeholder="Movie Comment / Description" class="form-control"></textarea>
                </div>

                <button type="submit"  id="save" class="btn btn-success btn-sm">Approve Request</button>
            </div>
        </div>
    </form>

@endsection
