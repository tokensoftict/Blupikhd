@extends('frontend.shared.main',$data)
@section('content')
    <div id="content-pro">
        @include('frontend.shared.registration_tabs',$data)
        <div id="pricing-plans-background-image">
            <div class="container">
                <div class="registration-steps-page-container">

                    <div class="registration-billing-form">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">

                                <div class="jumbotron jumbotron-fluid jumbotron-pro jumbotron-selected">
                                    <div class="container">
                                        <i class="fas fa-check-circle"></i>
                                        <img src="{{ asset('frontend') }}/images/demo/billing-credit-card.png" alt="Credit Card">
                                        <h6 class="light-weight-heading">Pay with Credit Card</h6>
                                    </div>
                                </div><!-- close .jumbotron -->

                            </div><!-- close .col-md -->
                        </div><!-- close .row -->


                        <div class="row">
                            <div class="billing-form-pro">
                                <form method="post" action="{{ route('user.top_up_wallet') }}?registration=true">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="billing-plan-container">
                                            <h5>Your Plan: @php $plan = \App\Plan::find(session()->get('plan_id'));  @endphp <a href="{{ route('registration.index') }}">Change plan</a></h5>
                                            <h3>{{ strtoupper($plan->name) }} : <span class="total">${{ number_format($plan->amount,0) }}</span><span class="duration">/month</span></h3>
                                        </div>
                                        <input type="hidden" value="{{ $plan->amount }}" name="amount"/>
                                        <button type="submit" class="btn btn-green-pro">Subscribe Now</button>
                                        <div class="clearfix"></div>
                                        @if(session('success'))
                                            {!! alert_success(session('success')) !!}
                                        @elseif(session('error'))
                                            {!! alert_error(session('error')) !!}
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div><!-- close .row -->

                            <hr/>
                    </div><!-- close .registration-billing-form -->

                </div><!-- close .registration-steps-page-container -->

            </div><!-- close .container -->
        </div>
    </div>
@endsection
