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
    <div class="panel-body table-responsive">
        <table class="table convert-data-table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $num = 1;
            @endphp
            @foreach($plans as $plan)
                <tr id="{{ $plan->id }}_tr">
                    <td>{{ $num }}</td>
                    <td>{{ $plan->name }}</td>
                    <td>{{ $plan->type }}</td>
                    <td>{{ number_format($plan->amount,2) }}</td>
                    <td>{!! $plan->status == "ACTIVE" ? label_success($plan->status) : label_warning($plan->status) !!}</td>
                    <td>{{ $plan->created_at }}</td>
                    <td>{{ $plan->updated_at }}</td>
                    <td><a href="{{ route('plan.edit',$plan->id) }}" class="btn btn-sm btn-success">Edit</a> </td>
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
