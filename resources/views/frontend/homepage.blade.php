@extends('frontend.shared.main',$data)

@section('extra_css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <style>
        .owl-nav{
            display:none;
        }
        .owl-dots{
            display:none;
        }
    </style>
@endsection

@section('content')
    @php
        $homepageSlider = getFrontPageSettings('sliders') ? getFrontPageSettings('sliders') : []
    @endphp
    <div class="owl-carousel">
        @if(count($homepageSlider) > 0)
            @foreach($homepageSlider as $slider)
                <div class="item"><img src="{{ asset('slider_image/'.$slider) }}" alt="Owl Image"></div>
            @endforeach
        @else
            <div class="item"><img src="{{ asset('frontend') }}/images/banner-1.png" alt="Owl Image"></div>
            <div class="item"><img src="{{ asset('frontend') }}/images/banner-2.png" alt="Owl Image"></div>
            <div class="item"><img src="{{ asset('frontend') }}/images/banner-3.png" alt="Owl Image"></div>
        @endif
    </div>
    <!-- close .progression-studios-slider - See /js/script.js file for options -->
    <div id="content-pro">

        <div class="container">

            @php
                $homepagetextentry = getFrontPageSettings('homepagetextentry') ? getFrontPageSettings('homepagetextentry') : []
            @endphp

            @if(count($homepagetextentry) > 0)
                @foreach( $homepagetextentry as $entry)
                    @if($entry['display'] == "1")
                        <div class="row">
                            <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                                <img src="{{ asset('home_text_entry/'.$entry['logo']) }}" class="img-fluid" alt="{{ $entry['title'] }}">
                            </div>
                            <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                                <h2 class="short-border-bottom">{{ $entry['title'] }}</h2>
                                <p>{{ $entry['text'] }}</p>
                                <div style="height:15px;"></div>
                               <!-- <p><a class="btn btn-green-pro" href="{{ route('registration.index') }}" role="button">Register Now</a></p> -->
                            </div>
                        </div><!-- close .row -->
                    @else
                        <div class="row">
                            <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                                <h2 class="short-border-bottom">{{ $entry['title'] }}</h2>
                                <p>{{ $entry['text'] }}</p>
                                <div style="height:15px;"></div>
                            <!-- <p><a class="btn btn-green-pro" href="{{ route('registration.index') }}" role="button">Start Watching</a></p> -->
                            </div>
                            <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                                <img src="{{ asset('home_text_entry/'.$entry['logo']) }}" class="img-fluid" alt="{{ $entry['title'] }}">
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="row">
                    <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                        <img src="{{ asset('frontend') }}/images/final-file.png" class="img-fluid" alt="Watch in Any Devices">
                    </div>
                    <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                        <h2 class="short-border-bottom">Watch on Devices and Platforms</h2>
                        <p>Watch our scheduled programmes on you mobile devices,laptops
                            our apps can easily be downloaded on to your android phone and tablet,apple phones and tablet
                            andriod tv box and apple tv box,tablets and on windows </p>
                        <div style="height:15px;"></div>
                        <p><a class="btn btn-green-pro" href="{{ route('registration.index') }}" role="button">Register Now</a></p>
                    </div>
                </div><!-- close .row -->

                <div class="row">
                    <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                        <h2 class="short-border-bottom">How our streaming keep rolling</h2>
                        <p>Login to watch amazing line-up packages 24/7, easily request for movies.music. documentary and other
                            amazing programmes you might missed on our daily schedules all life from our studio</p>
                        <div style="height:15px;"></div>
                        <p><a class="btn btn-green-pro" href="{{ route('registration.index') }}" role="button">Start Watching</a></p>
                    </div>
                    <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                        <img src="{{ asset('frontend') }}/images/keep_streaming.jpg" class="img-fluid" alt="How our streaming keep rolling">
                    </div>
                </div><!-- close .row -->

                <div class="row">
                    <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                        <img src="{{ asset('frontend') }}/images/4k_ultra.jpg" class="img-fluid" alt="Watch in Ultra HD">
                    </div>
                    <div class="col-md my-auto"><!-- .my-auto vertically centers contents -->
                        <h2 class="short-border-bottom">Watch in Ultra HD</h2>
                        <p>Watch our programmes shows in high quality resolutions ..no hanging, skipping,poor or blur quality display we come with the best resolution that could make you enjoy every single moment on our station.</p>
                        <div style="height:15px;"></div>
                        <p><a class="btn btn-green-pro" href="{{ route('registration.index') }}" role="button">Sign Up Now</a></p>
                    </div>
                </div><!-- close .row -->
            @endif

            <div style="height:35px;"></div>

            <div class="clearfix"></div>
        </div><!-- close .container -->


        <hr>

        <div class="progression-pricing-section-background">
            <div class="container">

                <div class="row">
                    <div class="mx-auto">
                        <div style="height:70px;"></div>
                        <h2 class="short-border-bottom">Our Plans &amp; Pricing</h2>
                    </div>
                </div><!-- close .row -->

                <div style="height:25px;"></div>

                <div class="row">
                    <div class="col-md">
                        <ul class="checkmark-list-pro">
                            <li>1 hour unlimited access!</li>
                            <li>Thousands of TV shows, movies &amp; more.</li>
                        </ul>
                    </div>
                    <div class="col-md">
                        <ul class="checkmark-list-pro">
                            <li>Stream on your phone, laptop, tablet or TV.</li>
                            <li>You can even Download & watch offline.</li>
                        </ul>
                    </div>
                    <div class="col-md">
                        <ul class="checkmark-list-pro">
                            <li>1 month unlimited access!</li>
                            <li>Thousands of TV shows, movies &amp; more.</li>
                        </ul>
                    </div>
                </div><!-- close .row -->


                <div class="pricing-table-pro">
                    <div class="row">
                        @php
                            $num = 1;
                        @endphp
                        @foreach($plans as $plan)
                            <div class="col-md">
                                <div class="pricing-table-col {{ $num==2 ? 'pricing-table-col-shadow-pro' : '' }}">
                                    <h6>{{ $plan->name }}</h6>
                                    <h2><sup>$</sup>{{ number_format($plan->amount,($plan->amount > 0 ? 0 : 2)) }}<span>  {{ $plan->amount >0 ? "/ ".ucwords(strtolower($plan->type)) : "" }}</span></h2>
                                    {!! $plan->web_description !!}
                                    <p><a class="btn" href="{{ route('registration.chooseplan',$plan->id) }}" role="button">Choose Plan</a></p>
                                </div>
                            </div>
                            @php
                                $num++;
                            @endphp
                        @endforeach
                    </div>
                </div>


                <div class="clearfix"></div>
            </div><!-- close .container -->

        </div><!-- close .progression-pricing-section-background -->

    </div><!-- close #content-pro -->
@endsection

@section('extra_js_scripts_files')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop:true,
                autoplay:true,
                autoHeight : true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:1
                    },
                    1000:{
                        items:1
                    }
                }
            })
        });
    </script>
@endsection
