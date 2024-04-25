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

    <form role="form" action="" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>Title</label>
            <input class="form-control" name="title" required  value="{{ old('title',$notification->title) }}"  placeholder="Title" type="text">
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" style="height: 190px;" placeholder="Message" name="message">{{ old('message',$notification->body) }}</textarea>
        </div>

        <button type="submit"  id="save" class="btn btn-success btn-lg">Update</button>
    </form>

@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection
