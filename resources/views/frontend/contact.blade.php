@extends('frontend.shared.main',$data)
@push('extra_css')
    <link href="{{ asset('frontend') }}/css/animate.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/owl.carousel.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset('frontend') }}/css/ada.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/ada-themes.css" rel="stylesheet">
@endpush
@section('content')

    <div id="page-title-pro">
        <div class="container">
            <div class="row">
                <h1>Contact Us</h1>
                <h6>Can’t find an answer? Email us at <a href="mailto:help@blupikhd.com">help@blupikhd.com</a></h6>
            </div><!-- close .row -->
        </div><!-- close .container -->
    </div>
    <div id="content-pro">
        <div id="single">
            <div class="container">
                <div class="post">
                    <div class="caption">

                        <div class="row contact-info">
                            <div class="col-md-6 text-center offset-md-3">
                                <i class="fa fa-envelope fa-4x"></i>
                                <h4>Email</h4>
                                <p>
                                    <a class="email" href="mailto:help@blupikhd.com">blupikhd@gmail.com</a>
                                </p>
                            </div>
                        </div>

                        <div class="header">
                            <h1>Drop us a line</h1>
                        </div>

                        <div class="row">
                            <div class="col-md-6 m-md-auto">
                                <form>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" placeholder="Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" placeholder="Email" required="">
                                    </div>
                                    <div class="form-group">
                                        <label for="company">Company</label>
                                        <input type="text" class="form-control" id="company" placeholder="Company">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea placeholder="Message" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-ada-dark">Send</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
