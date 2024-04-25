@extends('global.main')
@section('title',"Program Schedule Form")
@section('description',"Program Schedule Form")
@section('innerTitle',"Program Schedule Form")
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
    <form role="form" action="{{ route('setting.process') }}@if(isset($movie_re))?movie_request_id={{ $movie_re }}@endif" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        @if(isset($data->id))
            <input type="hidden" name="id" id="id" value="{{ $data->id }}">
        @endif
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" value="{{ old('title', $data->title) }}" placeholder="Programme Title" type="text" name="title">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Select Day</label>
                    <select id="day" class="form-control js-example-basic-select2" name="day" class="js-example-tags">
                        @php
                            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        @endphp
                        @foreach($days as $day)
                            <option {{ old('day', $data->day) == $day ? 'selected' : ''  }} value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    @if(!isset($data->id))
                    <label class="checkbox-inline">
                        <input id="inlineCheckbox1" value="1" name="all_day" type="checkbox"> Insert for all days of the Week
                    </label>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="text-center">From</label>
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input id="timepicker_from" value="{{ old('from', $data->from) }}" name="from" type="text" class="form-control">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="text-center">To</label>
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input id="timepicker_to" value="{{ old('to', $data->to) }}" name="to" type="text" class="form-control">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time fa fa-clock-o"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Programme Image</label>
                    @if(isset($data->programme_image))
                        <br/><br/>
                        <img src="{{ asset('programme_image/'.$data->programme_image) }}" width="15%" class="img-responsive"><br/>
                    @endif
                    <input type="file" name="programme_image" class="form-control" placeholder="Image"/>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Streaming Link</label>
                <input class="form-control" value="{{ old('link', $data->link) }}" placeholder="Streaming Link" type="text" name="link">
            </div>
        </div>
        </div>
          <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Status</label>
                 <input class="form-control" value="{{ old('status', $data->status) }}" placeholder="Status" type="text" name="status">
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="text-center">Programme Description</label>
                    <textarea class="form-control" placeholder="Programme Description" name="description" style="height: 70px;">{{ old('description', $data->description) }}</textarea>
                </div>
            </div>

        </div>

        <button type="submit"  id="save" class="btn btn-success btn-sm">SAVE PROGRAMME</button>
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
