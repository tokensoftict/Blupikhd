@extends('frontend.shared.main',$data)

@section('content')
    <div id="content-pro">
        @include('frontend.shared.registration_tabs',$data)
        <div id="pricing-plans-background-image">
            <div class="container">
                <div class="registration-steps-page-container">


                    <div class="registration-step-final-padding">
                        <h2 class="registration-final-heading">Hello <span>{{ auth('frontenduser')->user()->firstname }} {{ auth('frontenduser')->user()->lastname }}</span> Welcome to Blupikhd </h2>

                        <div class="registration-step-final-footer">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-green-pro">Finish</a>
                        </div>

                    </div>


                </div><!-- close .registration-steps-page-container -->

            </div><!-- close .container -->
        </div>
    </div>
@endsection
