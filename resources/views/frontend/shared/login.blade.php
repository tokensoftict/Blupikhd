<div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModal" aria-hidden="true">
    <button type="button" class="close float-close-pro" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header-pro">
                <h2>Welcome Back</h2>
                <h6>Sign in to your account to continue using Blupikhd</h6>
            </div>
            <div class="modal-body-pro social-login-modal-body-pro">

                <div class="registration-social-login-container">
                    <form action="{{ route('frontpagelogin.loginprocess') }}" method="post" id="submitlogin">
                        <div id="error_div"></div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="email" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <button type="submit" id="signin" class="btn btn-green-pro btn-display-block">Sign In</button>
                        </div>
                        <div class="container-fluid">
                            <div class="row no-gutters">
                                <div class="col forgot-your-password"><a href="#"  data-toggle="modal" data-target="#ForgotModal" >Forgot your password?</a></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="clearfix"></div>
            </div>

            <a class="not-a-member-pro" href="{{ route('registration.index') }}">Not a member? <span>Join Today!</span></a>
        </div>
    </div>
</div>


<div class="modal fade" id="ForgotModal" tabindex="-1" role="dialog" aria-labelledby="ForgotModal" aria-hidden="true">
    <button type="button" class="close float-close-pro" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header-pro">
                <h2>Forgot Password</h2>
            </div>
            <div class="modal-body-pro social-login-modal-body-pro">

                <div class="registration-social-login-container">
                    <form action="https://blupikhd.com/api/forgot_my_password" method="post" id="submitforgotpassword">
                        <div id="error_div_f"></div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="emailforgotpassword" placeholder="Email Address">
                        </div> 
                        <div class="form-group">
                            <button type="submit" id="forgotpassword" class="btn btn-green-pro btn-display-block">Reset Password</button>
                        </div>
                          <div class="form-group">
                            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn btn-danger btn-display-block">Cancel</button>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<script>
    window.onload = function(){
        $(document).ready(function(e){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var localStorage = window.localStorage;
            var btn = $('#signin');
            var error_div = $('#error_div');
            if(!localStorage){
                alert('Your browser does not support, web storage');
                btn.attr('disabled','disabled');
                error_div.html('<div class="alert alert-danger">Your browser does not support, web storage</div>')
            }
            $('#submitlogin').on('submit',function (e) {
                e.preventDefault();
                var email = $('#email').val();
                var password = $('#password').val();
                if(email === "" || password === "") return false;
                btn.attr('disabled','disabled').html('Please wait...');
                var data = {'email': email, 'password':password};
                error_div.html('');
                $.post($(this).attr('action'),data,function (response) {
                  if(response.status == false){
                      error_div.html('<div class="alert alert-danger">'+response.error+'</div>');
                  }else{
                      localStorage.setItem("access_code", response.user.device_key);
                      error_div.html('<div class="alert alert-success">Login Success</div>')
                      window.location.href = response.link;
                  }
                    btn.removeAttr('disabled','disabled').html('Sign In');
                }).fail(function(response) {
                    btn.removeAttr('disabled','disabled').html('Sign In');
                    alert('Error: ' + response.responseText);
                });
            })
            
            
            
            $('#submitforgotpassword').on('submit',function (e) {
                e.preventDefault();
                var btn = $('#forgotpassword');
                var error_div = $('#error_div_f');
                var email = $('#emailforgotpassword').val();
                if(email === "") return false;
                 error_div.html('');
                 var data = {'email': email};
                  $.post($(this).attr('action'),data,function (response) {
                       error_div.html('<div class="alert alert-success">'+response.message+'</div>')
                  });
            });

        });
    };

</script>
