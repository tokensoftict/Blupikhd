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
            <label>Select Subscriber</label>
            <select name="subscriber_id" required class="form-control js-example-basic-select2" style="width: 100%">
                <option>Select One</option>
                @foreach($subscribers as $subscriber)
                    <option value="{{ $subscriber->id }}">{{ $subscriber->firstname }} {{ $subscriber->firstname }} [ {{ $subscriber->email }}]</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Amount</label>
            <input type="number" required step="any" name="amount" class="form-control" placeholder="Amount" />
        </div>

        <button type="submit"  id="save" class="btn btn-success btn-lg">SAVE</button>
    </form>

@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection
