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
                <th>Email Address</th>
                <th>Username</th>
                <th>Phone Number</th>
                <th>Wallet Balance</th>
                @if(isset($type))
                    <th>Subscription ends</th>
                @endif
                <th>Country</th>
                <th>State</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
            $num = 1;
            @endphp
            @foreach($subscribers as $subscriber)
                <tr id="{{ $subscriber->id }}_tr">
                    <td>{{ $num }}</td>
                    <td>{{ $subscriber->firstname }} {{ $subscriber->firstname }}</td>
                    <td>{{ $subscriber->email }}</td>
                    <td>{{ $subscriber->username }}</td>
                    <td>{{ $subscriber->phoneno }}</td>
                    <td>{{ number_format($subscriber->wallet ,2) }}</td>
                    @if(isset($type))
                        <td>{{ str_date2($subscriber->subscription_expired_timestamp) }}</td>
                    @endif
                    <td>@php echo \App\Country::find($subscriber->country_id)->name  @endphp</td>
                    <td>@php echo  \App\State::find($subscriber->state_id)->name  @endphp</td>
                    <td>
                        <a href="{{ route("users.edit",$subscriber->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <a href="{{ route("users.delete",$subscriber->id) }}" class="btn btn-sm btn-danger">Delete</a>

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
