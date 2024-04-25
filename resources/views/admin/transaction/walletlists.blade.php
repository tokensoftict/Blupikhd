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
                <th>Name</th>
                <th>Email Address</th>
                <th>Transaction Type</th>
                <th>Transaction Amount</th>
                <th>Wallet Amount Before</th>
                <th>Wallet Amount After</th>
                <th>Transaction Date</th>
                <th>Recipient Name</th>
                 <th>Recipient Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
            $num = 1;
            @endphp
            @foreach($wallets as $wallet)
                <tr id="{{ $wallet->id }}_tr">
                    <td>{{ $num }}</td>
                    <td>{{ $wallet->user->firstname }} {{ $wallet->user->firstname }}</td>
                    <td>{{ $wallet->user->email }}</td>
                    <td>{{ $wallet->trans_type }}</td>
                    <td>{{ $wallet->sign }}{{ $wallet->currency }} {{ number_format($wallet->amount,2) }}</td>
                    <td>{{ number_format($wallet->wallet_amt_before ,2) }}</td>
                    <td>{{ number_format($wallet->wallet_amt_after ,2) }}</td>
                    <td>{{ str_date($wallet->transaction_date) }}</td>
                    <td>{{ $wallet->recipient->firstname }} {{ $wallet->recipient->firstname }}</td>
                    <td>{{ $wallet->recipient->email }}</td>
                    <td><a href="{{ route('transaction.delete_wallet_transaction',$wallet->id) }}" class="btn btn-sm btn-danger">Delete</a> </td>
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
