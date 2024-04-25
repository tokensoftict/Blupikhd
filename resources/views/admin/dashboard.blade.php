@extends('global.main',['dashboard'=>'dashboard'])
@section('title',"Dashboard")
@section('description',"Admin Dashboard")
@section('innerTitle',"Admin Dashboard")
@section('breadcrumb',"")
@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="panel short-states bg-danger">
                <div class="pull-right state-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="panel-body">
                    <h1 class="light-txt">{{ $subs_count }}</h1>
                    <div class=" pull-right">100% <i class="fa fa-bolt"></i></div>
                    <strong class="text-uppercase">SUBSCRIBERS</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel short-states bg-info">
                <div class="pull-right state-icon">
                    <i class="fa fa-google-wallet"></i>
                </div>
                <div class="panel-body">
                    <h1 class="light-txt">{{ $plans }}</h1>
                    <div class=" pull-right">100% <i class="fa fa-level-up"></i></div>
                    <strong class="text-uppercase">Total Plan</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel short-states bg-warning">
                <div class="pull-right state-icon">
                    <i class="fa fa-laptop"></i>
                </div>
                <div class="panel-body">
                    <h1 class="light-txt">${{ $wallet_total }}</h1>
                    <div class=" pull-right">100% <i class="fa fa-level-down"></i></div>
                    <strong class="text-uppercase">>Wallet Amount</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel short-states bg-primary">
                <div class="pull-right state-icon">
                    <i class="fa fa-pie-chart"></i>
                </div>
                <div class="panel-body">
                    <h1 class="light-txt">${{ $transacton }}</h1>
                    <div class=" pull-right">100% <i class="fa fa-level-up"></i></div>
                    <strong class="text-uppercase">Total Transactions</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 ">
            <div class="panel">
                <header class="panel-heading panel-border">
                    Recent Subscribers
                </header>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover latest-order">
                            <thead>
                             <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Wallet Balance</th>
                                @if(isset($type))
                                    <th>Subscription ends</th>
                                @endif
                                <th>Country</th>
                                <th>State</th>
                                <th>Created</th>
                                <th>Updated</th>
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
                                    <td>{{ $subscriber->phoneno }}</td>
                                    <td>{{ number_format($subscriber->wallet ,2) }}</td>
                                    @if(isset($type))
                                        <td>{{ str_date2($subscriber->subscription_expired_timestamp) }}</td>
                                    @endif
                                    <td>@php echo \App\Country::find($subscriber->country_id)->name  @endphp</td>
                                    <td>@php echo  \App\State::find($subscriber->state_id)->name  @endphp</td>
                                    <td>{{ $subscriber->created_at }}</td>
                                    <td>{{ $subscriber->updated_at }}</td>
                                </tr>
                                @php
                                    $num++;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 ">
            <div class="panel">
                <header class="panel-heading panel-border">
                   Recent Top Up Transaction
                </header>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover latest-order">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Date</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Updated</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $num = 1;
                            @endphp
                            @foreach($topups as $topup)
                                <tr id="{{ $topup->id }}_tr">
                                    <td>{{ $num }}</td>
                                    <td>{{ $topup->firstname }} {{ $topup->lastname }}</td>
                                    <td>{{ $topup->user->email }}</td>
                                    <td>{{ str_date2($topup->transaction_date) }}</td>
                                    <td>{{ $topup->country }}</td>
                                    <td>{{ $topup->state }}</td>
                                    <td>{{ $topup->phoneno }}</td>
                                    <td>@php
                                            $func =  "label_".strtolower($topup->status);
                                           echo  $func($topup->status, strtolower($topup->status))
                                        @endphp</td>
                                    <td>{{ $topup->updated_at }}</td>
                                </tr>
                                @php
                                    $num++;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
