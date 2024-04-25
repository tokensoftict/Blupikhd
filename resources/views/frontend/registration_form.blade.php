@extends('frontend.shared.main',$data)

@section('content')
    <div id="content-pro">

        @include('frontend.shared.registration_tabs',$data)

        <div id="pricing-plans-background-image">
            <div class="container">
                <div class="registration-steps-page-container">

                    <form class="registration-steps-form" method="post" action="">
                        {{ csrf_field() }}
                        <div class="registration-social-login-container">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="firstname" class="col-form-label">First Name</label>
                                        <input type="text" value="{{ old('firstname') }}" required class="form-control" name="firstname" id="firstname" placeholder="First Name">
                                        @if ($errors->has('firstname'))
                                            <label for="name-error" class="error"
                                                   style="display: inline-block;">{{ $errors->first('firstname') }}</label>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label for="lastname" class="col-form-label">Last Name</label>
                                        <input type="text" value="{{ old('lastname') }}" required class="form-control" name="lastname"  id="lastname" placeholder="Last Name">
                                        @if ($errors->has('lastname'))
                                            <label for="name-error" class="error"
                                                   style="display: inline-block;">{{ $errors->first('lastname') }}</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username" class="col-form-label">Username</label>
                                <input type="text"  value="{{ old('username') }}"  class="form-control" name="username" id="username" placeholder="Username">
                                @if ($errors->has('username'))
                                    <label for="name-error" class="error"
                                           style="display: inline-block;">{{ $errors->first('username') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email</label>
                                <input type="email" value="{{ old('email') }}" required class="form-control" id="email" name="email" placeholder="Email Address">
                                @if ($errors->has('email'))
                                    <label for="name-error" class="error"
                                           style="display: inline-block;">{{ $errors->first('email') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Phone Number</label>
                                <input type="text" value="{{ old('phoneno') }}" required class="form-control" name="phoneno" id="email" placeholder="Phone Number">
                                @if ($errors->has('phoneno'))
                                    <label for="name-error" class="error"
                                           style="display: inline-block;">{{ $errors->first('phoneno') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Country</label>
                                <select class="form-control" id="country" required name="country_id">
                                    <option>Select Country</option>
                                    @php
                                        $countries = \App\Country::all();
                                    @endphp
                                    @foreach($countries as $country)
                                        <option {{ ($country->id==old('country_id') ? 'selected' : '') }} value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('country_id'))
                                    <label for="name-error" class="error"
                                           style="display: inline-block;">{{ $errors->first('country_id') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">State</label>
                                <select class="form-control" required name="state_id" id="state">
                                    <option>Select State</option>
                                </select>
                                @if ($errors->has('state_id'))
                                    <label for="name-error" class="error"
                                           style="display: inline-block;">{{ $errors->first('state_id') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="password" class="col-form-label">Password</label>
                                        <input type="password" required class="form-control" name="password" id="password" placeholder="Password">
                                        @if ($errors->has('password'))
                                            <label for="name-error" class="error"
                                                   style="display: inline-block;">{{ $errors->first('password') }}</label>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label for="confirm-password" class="col-form-label">&nbsp;</label>
                                        <input type="password" required class="form-control" id="confirm-password" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group last-form-group-continue">
                            <button type="submit" class="btn btn-green-pro">Continue</button>
                            <span class="checkbox-remember-pro"><input type="checkbox" checked id="checkbox-terms"><label for="checkbox-terms" class="col-form-label">By clicking "Continue", you agree to our <a href="{{ route('terms') }}" target="_blank">Terms of Use</a> and
<a href="{{ route('terms') }}" target="_blank">Privacy Policy</a> including the use of cookies.</label></span>
                            <div class="clearfix"></div>
                        </div>
                    </form>

                </div><!-- close .registration-steps-page-container -->

            </div><!-- close .container -->
        </div>

    </div>
@endsection

@push('extra_js_scripts_files')
    <script>
        $(document).ready(function(){
            var old_state = '{{ old('country_id') }}';
            var old = '{{ old('state_id') }}';
            if(old_state != ''){
                $.get('{{ config('app.url') }}api/setting/state/'+old_state, function(response){
                    var html ="<option value=''>Select State</option>";
                    for(var i=0; i < response.length; i++){
                        html+="<option "+(old == response[i] ? "selected" : "")+">"+response[i]+"</option>";
                    }
                    $('#state').html(html);
                });
            }
            $('#country').on('change',function(){
                var id = $(this).val();
                $.get('{{ config('app.url') }}api/setting/state/'+id, function(response){
                    var html ="<option value=''>Select State</option>";
                    for(var i=0; i < response.length; i++){
                        html+="<option "+(old_state == response[i] ? "selected" : "")+">"+response[i]+"</option>";
                    }
                    $('#state').html(html);
                });
            })
        })
    </script>

@endpush
