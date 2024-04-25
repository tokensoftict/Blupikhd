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
                <th>Subscriber Name</th>
                <th>Title</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Title</th>
                <th>Status</th>
                <th>Request Date</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $num = 1;
            @endphp
            @foreach($movies_requests as $movies_request)
                <tr>
                    <td>{{ $num }}</td>
                    <td>{{ $movies_request->user->firstname }} {{ $movies_request->user->lastname }}</td>
                    <td>{{ $movies_request->title }}</td>
                    <td>{{ $movies_request->user->email }}</td>
                    <td>{{ $movies_request->user->phoneno }}</td>
                    <td>{{ $movies_request->title }}</td>
                    <td>{!! label_status($movies_request->status,strtolower($movies_request->status)) !!}</td>
                    <td>{{ str_date2($movies_request->request_date) }}</td>
                    <td>{{ $movies_request->created_at }}</td>
                    <td>{{ $movies_request->updated_at }}</td>
                    <td>
                        <a href="{{ route('subscribers.delete_movies_request',$movies_request->id) }}?page=series" class="btn btn-danger btn-sm">Delete</a>
                        @if($movies_request->status == "PENDING")
                            <a href="{{ route('subscribers.approve_movies_request',$movies_request->id) }}?page=series" class="btn btn-success btn-sm">Approve Request</a>
                        @endif
                        @if($movies_request->status == "APPROVED")
                            <a href="{{ route('subscribers.schedule_movies_request',$movies_request->id) }}?page=series" class="btn btn-primary btn-sm">Schedule Request</a>
                        @endif
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
