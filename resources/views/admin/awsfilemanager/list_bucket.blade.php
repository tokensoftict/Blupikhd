@extends('global.main')
@section('title',"List AWS Buckets")
@section('description','List AWS Buckets')
@section('innerTitle','List AWS Buckets')
@section('breadcrumb',"")

@section('extra_css_files')
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="panel-body table-responsive">
        <table class="table convert-data-table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $num = 1;
            @endphp
            @foreach($buckets as $bucket)
                <tr id="{{ $bucket->id }}_tr">
                    <td>{{ $num }}</td>
                    <td>{{ $bucket->name }}</td>
                    <td>{{ $bucket->created_at }}</td>
                    <td>{{ $bucket->updated_at }}</td>
                    <td>
                        <a href="{{ route('aws.list_files', $bucket->id) }}"  class="btn btn-sm btn-primary">View Files</a>
                        <a href="{{ route('aws.delete_bucket', $bucket->id) }}" onclick="return confirm('Are you sure, you want to delete this bucket, all files in the bucket will be deleted')"  class="btn btn-sm btn-danger">Delete Bucket</a>
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
