@extends('global.main')
@section('title',"Terms and Condition")
@section('description',"Terms and Condition")
@section('innerTitle',"Terms and Condition")
@section('breadcrumb',"")

@section('extra_css_files')
    <link rel="stylesheet" href="{{ asset('bower_components/summernote/dist/summernote.css') }}">
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
            <label>Terms and Condition</label>
            <textarea id="summernote" name="terms_condition">{{ old('terms_condition', $settings->terms_condition) }}</textarea>
        </div>

        <button type="submit"  id="save" class="btn btn-success btn-sm">SAVE</button>
    </form>
@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/summernote/dist/summernote.js') }}"></script>
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: true                  // set focus to editable area after initializing summernote
            });

        });
    </script>
@endsection
