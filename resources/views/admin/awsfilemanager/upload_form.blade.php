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

    <form action="{{ route('aws.uploadSingle') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="form-group">
            <label for="name">Select Bucket:</label>
            <select class="form-control" name="bucket_key">
                @foreach($buckets as $bucket)
                    <option value="{{ $bucket->key }}">{{ $bucket->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="file">File: Max({{ getMaximumFileUploadSize() }})</label>
            <input type="file" class="form-control" id="file" name="file">
        </div>
        <button type="submit" class="btn btn-primary">Upload File</button>
    </form>

@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection