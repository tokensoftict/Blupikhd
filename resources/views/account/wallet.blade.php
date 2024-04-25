@extends('account.shared.main')
@section('breadcrumb',"")
@section('content')
    <div class="dashboard-container">

        <ul class="dashboard-sub-menu">
            <li><a href="{{ route('user.profile') }}">Account Settings</a></li>
            <li class="current"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
        </ul><!-- close .dashboard-sub-menu -->

        <h5>My Wallet</h5>

        <div class="current-plan-account">

            @if(session('success'))
                {!! alert_success(session('success')) !!}
            @elseif(session('error'))
                {!! alert_error(session('error')) !!}
            @endif

            <div class="row">
                <div class="col-md">
                    <h4>Balance</h4>
                    <h2><sup>$</sup>{{ $profile->wallet }}</h2>
                    <p class="small-paragraph-spacing"><a href="#!"  data-toggle="modal" data-target="#topWallet" class="btn btn-sm btn-green-pro">Top Up Wallet</a></p>
                </div><!-- close .col-sm -->
                <div class="col-md">
                    <p class="small-paragraph-spacing"><a href="#!" data-toggle="modal" data-target="#subscriptionModal" role="button" class="btn">Subscription</a></p>
                    <p class="small-paragraph-spacing"><a href="#!" data-toggle="modal" data-target="#transferFund" role="button" class="btn btn-green-pro">Transfer Funds</a></p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="current-plan-billing text-danger">{{ $profile->expirymessage }}</div>
            <hr/>


        </div>

        <h5>Transactions</h5>
        <div class="table-responsive">
            <table class="table table-striped  table-bordered table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction Type</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Balance</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $num = 1;
                @endphp
                @foreach($profile->transactions()->orderBy('id','DESC')->get() as $transaction)
                    <tr>
                        <td>{{ $num }}</td>
                        <td>{{ $transaction->trans_type }}</td>
                        <td>{{ $transaction->mode }}</td>
                        <td>{{ $transaction->sign }} {{ $transaction->currency }} {{ number_format($transaction->amount,2) }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ number_format($transaction->wallet_amt_after,2) }}</td>
                    </tr>
                    @php
                        $num++;
                    @endphp
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-labelledby="subscriptionModal" aria-hidden="true">
        <button type="button" class="close float-close-pro" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header-pro">
                    <h2>Subscribe to Plan</h2>
                    <h6>Select Your Preferred Subscription Plan</h6>
                </div>
                <div class="modal-body-pro social-login-modal-body-pro">
                    <div class="registration-social-login-container">
                        <form action="{{ route('user.subscribe_to_plan') }}" method="post">
                            <div id="error_sub_div"></div>
                            @foreach($plans as $plan)
                                <div class="row no-gutters">
                                    <div class="col checkbox-remember-pro pt-2 pb-2"><input type="radio" value="{{ $plan->id }}" name="plan" id="checkbox-remember"><label for="checkbox-remember" class="col-form-label font-bold" style="font-size: 16px; color: #000">{{ $plan->name }}</label></div>
                                </div>
                            @endforeach
                            <button type="submit" role="button" class="btn btn-block btn-green-pro">Subscribe Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transferFund" tabindex="-1" role="dialog" aria-labelledby="transferFund" aria-hidden="true">
        <button type="button" class="close float-close-pro" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header-pro">
                    <h2>Fund Transfer</h2>
                    <h6>Transfer Fund to Friends</h6>
                </div>
                <div class="modal-body-pro social-login-modal-body-pro">
                    <div class="registration-social-login-container">
                        <form action="{{ route('user.transfer_fund') }}" method="post">
                            <div id="error_transfer_div"></div>
                            <div class="form-group">
                                <label>Recipient Email Address</label>
                                <input type="text" class="form-control" required id="rec_email" name="email_address" placeholder="Recipient Email Address">
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" class="form-control" required  name="amount" id="amount" placeholder="Amount">
                            </div>
                            <button type="submit" role="button" class="btn btn-block btn-green-pro">Transfer Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="topWallet" tabindex="-1" role="dialog" aria-labelledby="topWallet" aria-hidden="true">
        <button type="button" class="close float-close-pro" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header-pro">
                    <h2>Top Up</h2>
                    <h6>Top up Your Wallet</h6>
                </div>
                <div class="modal-body-pro social-login-modal-body-pro">
                    <div class="registration-social-login-container">
                        <form action="{{ route('user.top_up_wallet') }}" method="post">
                            {{ csrf_field() }}
                            <div id="error_wallet_div"></div>
                            <div id="error_topup_div"></div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" class="form-control"  name="amount" required id="amount" placeholder="Amount">
                            </div>
                            <div class="form-group">
                                <label>Mode of Payment</label>
                                <select class="form-control" name="payment_method">
                                    <option>Select Mode</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                </select>
                            </div>
                            <button type="submit" role="button" class="btn btn-block btn-green-pro">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        window.onload = function(){
            $(document).ready(function(e){

                @if($topup == true)
                   $('#topWallet').modal('show');
                @endif

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var btn = $('#subscriptionModal .btn-green-pro');
                var error_sub_div = $('#error_sub_div');
                $('#subscriptionModal form').on('submit',function(e){
                    e.preventDefault();
                    error_sub_div.html('');
                    var serializeData = $(this).serializeArray();
                    if(serializeData.length > 0) {
                        btn.attr('disabled','disabled').html('Please wait...');
                        let data = {};
                        for (let i = 0; i < serializeData.length; i++) {
                            data[serializeData[i]['name']] = serializeData[i]['value'];
                        }
                        $.post($(this).attr('action'),data,function (response) {
                            if(response.status == false){
                                error_sub_div.html('<div class="alert alert-danger">'+response.error+'</div>');
                            }else{
                                error_sub_div.html('<div class="alert alert-success">'+response.message+'</div>');
                                setTimeout(function(){
                                    window.location.reload();
                                },2000)
                            }
                            btn.removeAttr('disabled','disabled').html('Subscribe Now');
                        });

                    }else{
                        error_sub_div.html('<div class="alert alert-danger">Please select a Subscription Plan.</div>')
                    }
                })

                $('#transferFund form').on('submit',function(e){
                    e.preventDefault();
                    var btn = $('#transferFund .btn-green-pro');
                    var error_transfer_div = $('#error_transfer_div');
                    var serializeData = $(this).serializeArray();
                    if(serializeData.length > 0) {
                        btn.attr('disabled','disabled').html('Please wait...');
                        let data = {};
                        for (let i = 0; i < serializeData.length; i++) {
                            data[serializeData[i]['name']] = serializeData[i]['value'];
                        }
                        $.post($(this).attr('action'),data,function (response) {
                            if(response.status == false){
                                error_transfer_div.html('<div class="alert alert-danger">'+response.error+'</div>');
                            }else{
                                error_transfer_div.html('<div class="alert alert-success">'+response.message+'</div>');
                                setTimeout(function(){
                                    window.location.reload();
                                },2000)
                            }
                            btn.removeAttr('disabled','disabled').html('Subscribe Now');
                        });
                    }else{
                        error_transfer_div.html('<div class="alert alert-danger">All Fields are Required</div>')
                    }

                });

            })
        }
    </script>
@endsection

