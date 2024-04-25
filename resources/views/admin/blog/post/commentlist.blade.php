@extends('global.main')
@section('title',"List Post Comment")
@section('description',"List Post Comment")
@section('innerTitle',"List Post Comment")
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

    <table class="table convert-data-table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>By</th>
            <th>Comments</th>
            <th>Status</th>
            <th>Created Time</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $comment->user->firstname }} {{ $comment->user->lastname }}</td>
                <td>{{ $comment->content_html }}</td>
                <td>{!!   status($comment->status) !!}</td>
                <td>{{ $comment->created_at }}</td>
                <td>
                    @if($comment->status != "PUBLISHED")
                        <a class="btn btn-sm btn-primary" href="{{ route('blog.approved_comments', $comment->id) }}">Approve</a>
                
                    @endif
                     <a class="btn btn-sm btn-danger" href="{{ route('blog.delete_comments', $comment->id) }}">Delete</a>
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
