@extends('global.main')
@section('title',"List AWS Files")
@section('description','List AWS Files')
@section('innerTitle','List AWS Files')
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
                <th>Description</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $num = 1;
            @endphp
            @foreach($files as $file)
                <tr id="{{ $file->id }}_tr">
                    <td>{{ $num }}</td>
                    <td>{{ $file->name }}</td>
                    <td>{{ $file->descriptions }}</td>
                    <td>{{ $file->created_at }}</td>
                    <td>{{ $file->updated_at }}</td>
                    <td>
                        <a href="#" onclick="return copyLink(this)" data-url="https://d3y6.c11.e2-1.dev/{{ $file->awsBucket->key }}/{{ $file->aws_name }}" class="btn btn-sm btn-primary">Copy Url</a>
                        &nbsp; &nbsp;
                        <a href="{{ route('aws.delete', $file->id) }}" onclick="return confirm('Are you sure, you want to delete ?')" class="btn btn-sm btn-danger">Delete</a>
                        &nbsp; &nbsp;
                        <a target="_blank" href="{{ 'https://d3y6.c11.e2-1.dev/'. $file->awsBucket->key .'/'. $file->aws_name  }}" class="btn btn-sm btn-info">Download</a>
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
    <script>
        function copyLink(button)
        {
            var copyText = $(button).attr('data-url');

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText);

            // Alert the copied text
            alert("Copied the text: " + copyText);
            return false;
        }
    </script>
@endsection
