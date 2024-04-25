@extends('global.main')
@section('title',$page_title)
@section('description',$page_title)
@section('innerTitle',$page_title)
@section('breadcrumb',"")

@section('extra_css_files')
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
@endsection


@section('content')
    @if(session('success'))
        {!! alert_success(session('success')) !!}
    @elseif(session('error'))
        {!! alert_error(session('error')) !!}
    @endif

    <div class="panel-body table-responsive">
        <table class="table convert-data-table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Push Message</th>
                <th>Type</th>
                <th>No of Device</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $num = 1;
            @endphp
            @foreach($lists as $list)
                <tr>
                    <td>{{ $num }}</td>
                    <td>{{ $list->title }}</td>
                    <td>{{ $list->body }}</td>
                    <td>{{ $list->type }}</td>
                    @if($list->type == "topic")
                        <td>All</td>
                    @else
                        <td>{{ $list->no_of_device }}</td>
                @endif
                <!--  <td>{{ $list->total_view }}</td>
                    <td>{{ $list->total_sent }}</td>
                    <td>{!! status($list->status) !!}</td> -->
                    <td>
                        @if($list->status == "DRAFT")
                            <a class="confirm_action btn btn-success btn-sm" data-msg="Are you sure you want to send this Push Message ?" href="{{ route('push.send_push',$list->id) }}">Push Now </a>
                        @endif
                        <a class="confirm_action btn btn-danger btn-sm"  data-msg="Are you sure you want to delete this Push Message ?" href="{{ route('subscribers.delete_notification',$list->id) }}">Delete </a>
                        <a class=" btn btn-success btn-sm"   href="{{ route('subscribers.update_notification',$list->id) }}">Edit </a>
                    </td>
                </tr>
                @php
                    $num++;
                @endphp
            @endforeach
            </tbody>
        </table>
    </div>


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
