@extends('frontend.shared.main',$data)

@push('extra_css')
    <link href="{{ asset('frontend') }}/css/animate.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/owl.carousel.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset('frontend') }}/css/ada.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/ada-themes.css" rel="stylesheet">
@endpush

@push('blog_meta')
    <meta property="og:title" content="{{ $post->title }}">
    <title>{{ $post->title }}</title>
    <meta property="og:type" content="News">
    <meta property="og:description" content="{{ $post->title }}">
    <meta property="og:url" content="{{ $post->permalink }}">
    
<meta name="description" content="{{ $post->excerpt }}">
<meta name="twitter:description" content="{{ $post->excerpt }}">
<meta property="og:description" content="{{ $post->excerpt }}">

<meta property="og:image" content="{{ $post->blogimageurl }}">
<meta name="twitter:image" content="{{ $post->blogimageurl }}">
<meta property="og:title" content="{{ $post->title }}">
<meta name="twitter:title" content="{{ $post->title }}">
<meta property="og:url" content="{{ url('/') }}">
    
@endpush

@section('content')
    <div id="single">

        <div class="container">

            <div class="post">
                <img src="{{ $post->blogimageurl }}" class="img-fluid">

                <div class="caption">
                    <div class="header">
                        Tags <span class="post-tag">{{ implode(", #",explode(",",$post->seo_keywords)) }}</span>
                        <h1>{{ $post->title }}</h1>
                    </div>
                    {!! $post->content_html !!}
                </div>
            </div>
            {!! Adsense::show('responsive') !!}
            <div class="comments">
                <h2>Leave a Comment:</h2>
                @if(auth('frontenduser')->check())
                <div class="card my-4">
                    <h5 class="card-header">Write Your Comment below:</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('comment',$post->id) }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea required name="comment" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                @else
                    <div class="card my-4">
                        <h5 class="card-header">Write Your Comment below:</h5>
                        <div class="card-body">
                            <a href="#" data-toggle="modal" data-target="#LoginModal" class="btn btn-lg btn-green-pro">Login to add Comment</a>
                        </div>
                    </div>
                @endif
                @foreach($post->postComments()->orderBy('id','DESC')->get() as $comment)
                <div class="media mb-4">
                    <img class="d-flex mr-3 rounded-circle" src="{{ asset('frontend') }}/images/1.png" width="40" alt="">
                    <div class="media-body">
                        <h5 class="mt-0">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</h5>
                        {{ $comment->content_html }}
                    </div>
                </div>
                @endforeach
            </div>


        </div>

    </div>

@endsection

@push('extra_js_scripts_files')
    <script src="{{ asset('frontend') }}/js/owl.carousel.js"></script>
    <script src="{{ asset('frontend') }}/js/smoothScroll.js"></script>
    <script src="{{ asset('frontend') }}/js/wow.min.js"></script>
@endpush
