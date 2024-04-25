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
            <section class="col-md-12 wow fadeIn">
              {!! \App\Setting::find(1)->terms_condition !!}
            </section>
        </div>
    </div>
@endsection
