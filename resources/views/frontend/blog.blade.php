@extends('frontend.shared.main',$data)

@push('extra_css')
    <link href="{{ asset('frontend') }}/css/animate.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/owl.carousel.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset('frontend') }}/css/ada.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/ada-themes.css" rel="stylesheet">
@endpush

@section('content')
    <div id="posts" class="container">
        <div class="row">
            @foreach($popularpost as $popular)
                <section class="col-md-12 wow fadeIn">
                    <div class="post">
                        <div class="row">
                            <div class="col-md-6 media">
                                <img src="{{ $popular->blogimageurl }}" class="img-fluid" />
                            </div>
                            <div class="col-md-6 caption">
                                    <span class="post-tag">{{ implode(", #",explode(",",$popular->seo_keywords)) }}</span>
                                <a href="{{ $popular->permalink }}" class="post-title">{{ $popular->title }}</a>
                                <span class="post-date">{{ str_date($popular->published_at) }}</span>
                                <p class="post-description">{{ $popular->excerpt }}...</p>
                            </div>
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                {!! Adsense::show('rectangle') !!}
            </div>
        </div>
        <div class="row">
            @foreach($posts as $post)
                <section class="col-md-4 wow fadeIn">
                    <div class="post">
                        <div class="media">
                            <div id="slides-1">
                                <img src="{{ $post->blogimageurl }}" class="img-fluid">
                            </div>
                        </div>
                        <div class="caption text-center">
                            <a href="{{ $post->permalink }}" class="post-title">{{ $post->title }}</a>
                            <p class="post-description">{{ $post->excerpt }}...</p>
                            <span class="post-date">{{ str_date($post->published_at) }}</span>
                        </div>

                    </div>
                </section>
            @endforeach
        </div>
    </div>
@endsection

@push('extra_js_scripts_files')
    <script src="{{ asset('frontend') }}/js/owl.carousel.js"></script>
    <script src="{{ asset('frontend') }}/js/smoothScroll.js"></script>
    <script src="{{ asset('frontend') }}/js/wow.min.js"></script>
@endpush
