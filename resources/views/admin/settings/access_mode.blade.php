@extends('global.main')
@section('title',"Access Mode")
@section('description',"Access Mode")
@section('innerTitle',"Access Mode")
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
    <form role="form" action="" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id" value="1">

        <div class="form-group">
            <label>Access Mode</label>
            <select id="postTag" class="form-control js-example-basic-select2" name="access_mode"  style="width: 100%">
                <option value="FREE" @if($data->access_mode == "FREE") selected @endif>FREE</option>
                <option  value="PAID" @if($data->access_mode == "PAID") selected @endif>PAID</option>
            </select>
        </div>

        <div class="form-group">
            <label>Streaming Url</label>
            <input  value="{{ old('streamning_url',$data->streamning_url) }}" name="streamning_url" type="text" class="form-control">
        </div>

        <button type="submit"  id="save" class="btn btn-success btn-sm">SAVE CHANGES</button>
        <br/>
        <br/>
         <a href="{{ url('facebook/auth') }}" target="_new" class="btn btn-lg btn-primary btn-block">Authenticate Facebook for Posting</a>
    </form>
@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection
