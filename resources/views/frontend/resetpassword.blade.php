@extends('frontend.shared.main',$data)
@push('extra_css')
    <link href="{{ asset('frontend') }}/css/animate.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/owl.carousel.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset('frontend') }}/css/ada.css" rel="stylesheet">
    <link href="{{ asset('frontend') }}/css/ada-themes.css" rel="stylesheet">
@endpush
@section('content')

<div class="container">
    
    <div class="row">
             <div class="col-md-6 offset-3">
                 <br/> <br/> <br/> <br/>
                      <div class="registration-social-login-container">
                @if($user)        
                    <form action="" method="post">
                         {{ csrf_field() }}
                          <h2>Reset Password</h2>
                        <div id="error_div"></div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="conpassword" id="conpassword" placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <button type="submit"  class="btn btn-green-pro btn-display-block">Reset Password</button>
                        </div>
                    </form>
                    
                    @elseif(isset($success) && $success=="success")
                       <h3 class="text-success" align="center">Password has been changed successfully!.</h2>
                    <p><a href="{{ config('app.url') }}" class="btn btn-success btn-block">Continue</a></p>
                    @else
                    <h3 class="text-danger" align="center">Invalid Token or Token has expired, Please try again</h2>
                    <p><a href="{{ config('app.url') }}" class="btn btn-success btn-block">Continue</a></p>
                    
                 @endif   
                     <br/> <br/> <br/> <br/>
                </div>
          </div>
    </div>
    
</div>

@endsection