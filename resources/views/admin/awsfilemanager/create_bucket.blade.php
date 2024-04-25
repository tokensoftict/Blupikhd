@extends('global.main')
@section('title',"AWS File Upload")
@section('description',"Upload File to Aws S3 Bucket")
@section('innerTitle',"AWS File Upload")
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
    <form action="{{ route('aws.create') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Bucket Name:</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <button type="submit" class="btn btn-primary">Create Bucket</button>
    </form>
    <br>
@endsection




@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection