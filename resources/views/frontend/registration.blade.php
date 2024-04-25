@extends('frontend.shared.main',$data)

@section('content')
    <div id="content-pro">

        @include('frontend.shared.registration_tabs',$data)

        <div id="pricing-plans-background-image">
            <div class="container">
                <div class="pricing-plans-page-container">

                    <div class="row">
                        @php
                            $num = 1;
                        @endphp
                        @foreach($plans as $plan)
                            <div class="col-sm">
                                <div class="pricing-table-col">
                                    <h6>{{ $plan->name }}</h6>
                                    <h2><sup>$</sup>{{ number_format($plan->amount,($plan->amount > 0 ? 0 : 2)) }}<span>  {{ $plan->amount >0 ? "/ ".ucwords(strtolower($plan->type)) : "" }}</span></h2>
                                    {!! $plan->web_description !!}
                                    <p><a class="btn {{ $num==2 ? 'btn-green-pro' : '' }}" href="{{ route('registration.chooseplan',$plan->id) }}" role="button">Choose Plan</a></p>
                                </div>
                            </div>
                            @php
                                $num++;
                            @endphp
                        @endforeach
                    </div><!-- close .row -->

                </div><!-- close .pricing-plans-page-container -->
            </div><!-- close .container -->
        </div><!-- close #pricing-plans-background-image -->

    </div>
@endsection
