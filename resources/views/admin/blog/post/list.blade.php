@extends('global.main')
@section('title',"List Blog Post")
@section('description',"List Blog Post")
@section('innerTitle',"List Blog Post")
@section('breadcrumb',"")

@section('extra_css_files')
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
@endsection

@section('panel_controls')
    <a href="{{ route('blog.create') }}" id="create" class="btn btn-sm btn-success add">New Post</a>
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
                <th>Featured Image</th>
                <th>Status</th>
                <th>Created Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->title  }}</td>
                    <td>@if(isset($post->image))<img src="{{ asset('post_image/'.$post->image) }}" style="width: 30%" class="img-fluid" />@endif</td>
                    <td>{!!   status($post->status) !!}</td>
                    <td>{{ $post->created_at }}</td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="{{ route('blog.edit', $post->id) }}">Edit</a>
                        <a class="btn btn-sm btn-danger" href="{{ route('blog.delete', $post->id) }}">Delete</a>
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
