@extends('global.main')
@section('title',"Programme Schedule List")
@section('description',"Programme Schedule List")
@section('innerTitle',"Programme Schedule List")
@section('breadcrumb',"")

@section('extra_css_files')
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
@endsection


@section('panel_controls')
    <a href="{{ route('setting.form') }}" id="create" class="btn btn-sm btn-success add">New Programme Schedule</a>
@endsection

@section('content')
    @if(session('success'))
        {!! alert_success(session('success')) !!}
    @elseif(session('error'))
        {!! alert_error(session('error')) !!}
    @endif

    <table class="table convert-data-table table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Image</th>
            <th>Day</th>
            <th>From</th>
            <th>To</th>
            <th>Status</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $sche)
            <tr>
                <td>{{ $sche->title  }}</td>
                <td><img src="{{ asset('programme_image/'.$sche->programme_image) }}" width="40"/> </td>
                <td>{{ $sche->day  }}</td>
                <td>{{ $sche->from_string  }}</td>
                <td>{{ $sche->to_string  }}</td>
                 <td>{{ $sche->status  }}</td>
                <td>{{ $sche->updated_at }}</td>
                <td>{{ $sche->created_at }}</td>
                <td>
                    <a class="btn btn-sm btn-primary" href="{{ route('setting.edit', $sche->id) }}">Edit</a>
                    <a class="btn btn-sm btn-danger" href="{{ route('setting.delete', $sche->id) }}">Delete</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{  asset('bower_components/datatables-tabletools/js/dataTables.tableTools.js') }}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-colvis/js/dataTables.colVis.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('bower_components/datatables-scroller/js/dataTables.scroller.js') }}"></script>

    <!--init data tables-->
    <script src="{{ asset('js/init-datatables.js') }}"></script>
@endsection
