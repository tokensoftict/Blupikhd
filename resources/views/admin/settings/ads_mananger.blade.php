@extends('global.main')
@section('title',"Ads Manager Settings")
@section('description',"Ads Manager Settings")
@section('innerTitle',"Ads Manager Settings")
@section('breadcrumb',"")

@section('extra_css_files')
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">'        <!-- Bootstrap Date Range Picker Dependencies -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">

@endsection


@section('content')
    @if(session('success'))
        {!! alert_success(session('success')) !!}
    @elseif(session('error'))
        {!! alert_error(session('error')) !!}
    @endif
    <form role="form" action="" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group">
            <label>Select Main Ads Network</label>
            <select class="form-control" name="configured_ad_network">
                 <option {{ old('configured_ad_network',$settings->configured_ad_network) == "OFF" ? 'selected' : '' }} value="OFF">OFF</option>
                <option {{ old('configured_ad_network',$settings->configured_ad_network) == "facebook" ? 'selected' : '' }} value="facebook">Facebook ADS</option>
                <option {{ old('configured_ad_network',$settings->configured_ad_network) == "goggle" ? 'selected' : '' }} value="goggle">Google ADMob</option>
            </select>
        </div>
<br/>

<hr/>
        <h4>Facebook Ads Settings</h4>

        <div class="form-group">
            <label>Facebook Ads Testing Mode</label>
            <select class="form-control" name="facebook_ads_status">
                <option {{ old('facebook_ads_status',$settings->facebook_ads_status) == "LIVE" ? 'selected' : '' }} value="LIVE">LIVE</option>
                <option {{ old('facebook_ads_status',$settings->facebook_ads_status) == "TEST" ? 'selected' : '' }} value="TEST">TEST</option>
            </select>
        </div>

        <!--
        <div class="form-group">
            <label>Facebook Testing Ads Key</label>
            <input  value="{{ old('facebook_testingid_texting_ads_key',$settings->facebook_testingid_texting_ads_key) }}" name="facebook_testingid_texting_ads_key" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>Facebook Interstitial Android Ads Placementid</label>
            <input  value="{{ old('facebook_Interstitial_placementid',$settings->facebook_Interstitial_placementid) }}" name="facebook_Interstitial_placementid" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>Facebook Interstitial IOS Placementid</label>
            <input  value="{{ old('facebook_ios_Interstitial_placementid',$settings->facebook_ios_Interstitial_placementid) }}" name="facebook_ios_Interstitial_placementid" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>Facebook Rewarded Android Ads Placementid</label>
            <input  value="{{ old('facebook_rewardedvideoad_placementid',$settings->facebook_rewardedvideoad_placementid) }}" name="facebook_rewardedvideoad_placementid" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>Facebook Rewarded IOS Ads Placementid</label>
            <input  value="{{ old('facebook_rewardedvideoad_ios_placementid',$settings->facebook_rewardedvideoad_ios_placementid) }}" name="facebook_rewardedvideoad_ios_placementid" type="text" class="form-control">
        </div>
-->
        <div class="form-group">
            <label>Facebook Banner Ads Placementid</label>
            <input  value="{{ old('facebook_bannerAd',$settings->facebook_bannerAd) }}" name="facebook_bannerAd" type="text" class="form-control">
        </div>

        <div class="form-group">
            <label>Facebook Banner Ads IOS Placementid</label>
            <input  value="{{ old('facebook_ios_bannerAd',$settings->facebook_ios_bannerAd) }}" name="facebook_ios_bannerAd" type="text" class="form-control">
        </div>


        <hr/>
        <h4>Google Settings</h4>

        <div class="form-group">
            <label>Google Ads Testing Mode</label>
            <select class="form-control" name="google_ads_status">
                <option {{ old('google_ads_status',$settings->google_ads_status) == "LIVE" ? 'selected' : '' }} value="LIVE">LIVE</option>
                <option {{ old('google_ads_status',$settings->google_ads_status) == "TEST" ? 'selected' : '' }} value="TEST">TEST</option>
            </select>
        </div>

        <div class="form-group">
            <label>Google Interstitial Ads Unit ID</label>
            <input  value="{{ old('google_Interstitial_adunitid',$settings->google_Interstitial_adunitid) }}" name="google_Interstitial_adunitid" type="text" class="form-control">
        </div>
<!--
        <div class="form-group">
            <label>Google Rewarded Ads Unit ID</label>
            <input  value="{{ old('google_rewardedvideoad_adunitid',$settings->google_rewardedvideoad_adunitid) }}" name="google_rewardedvideoad_adunitid" type="text" class="form-control">
        </div>
-->
        <div class="form-group">
            <label>Google Banner Ads Placementid</label>
            <input  value="{{ old('google_bannerAd',$settings->google_bannerAd) }}" name="google_bannerAd" type="text" class="form-control">
        </div>



        <hr/>
        <h4>IOS Ads Settings</h4>

        <div class="form-group">
            <label>IOS Ads Testing Mode</label>
            <select class="form-control" name="ios_ads_status">
                <option {{ old('ios_ads_status',$settings->ios_ads_status) == "LIVE" ? 'selected' : '' }} value="LIVE">LIVE</option>
                <option {{ old('ios_ads_status',$settings->ios_ads_status) == "TEST" ? 'selected' : '' }} value="TEST">TEST</option>
            </select>
        </div>

        <div class="form-group">
            <label>IOS Interstitial Ads Unit ID</label>
            <input  value="{{ old('ios_Interstitial_adunitid',$settings->ios_Interstitial_adunitid) }}" name="ios_Interstitial_adunitid" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label>IOS Banner Ads Placement ID</label>
            <input  value="{{ old('ios_bannerAd',$settings->ios_bannerAd) }}" name="ios_bannerAd" type="text" class="form-control">
        </div>

        <button type="submit"  id="save" class="btn btn-success btn-sm">SAVE CHANGES</button>
    </form>
@endsection


@section('extra_js_files')
    <script src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>

    <script src="{{ asset('js/init-timepicker.js') }}"></script>
    <script src="{{ asset('js/init-switchery.js') }}"></script>
    <script src="{{ asset('js/init-daterangepicker.js') }}"></script>
    <script src="{{ asset('js/init-select2.js') }}"></script>
@endsection
