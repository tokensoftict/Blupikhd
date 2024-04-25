<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('ico.ico') }}" />
    <title>{{ config('app.name', 'Blupikhd Administrator Backend') }}</title>

    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('bower_components/simple-line-icons/css/simple-line-icons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('bower_components/weather-icons/css/weather-icons.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('bower_components/themify-icons/css/themify-icons.css') }}"/>

    @yield('extra_css_files')

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/rickshaw/rickshaw.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/jquery-easy-pie-chart/easypiechart.css') }}">
    <link rel="stylesheet" href="{{ asset('js/horizontal-timeline/css/style.css') }}">
    <script src="{{ asset('js/modernizr-custom.js') }}"></script>
</head>
<body>

<div id="ui" class="ui">
    @include('global.header')

    @include('global.aside')

    <div id="content" class="ui-content ui-content-aside-overlay">
        <div class="ui-content-body">
            <div class="ui-container">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="page-title">  @yield('title')
                            <small> @yield('description')</small>
                        </h1>
                    </div>
                    <div class="col-md-4">
                        <ul class="breadcrumb pull-right">
                            @yield('breadcrumb')
                        </ul>
                    </div>
                </div>
                @if(isset($dashboard))
                    @yield('content')
                    @else
                    <div class="col-md-12">
                        <div class="panel">
                            <header class="panel-heading">
                                @yield('innerTitle')
                                <div class="pull-right">
                                    @yield('panel_controls')
                                </div>
                            </header>
                            <div class="panel-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>

    @include('global.footer')
</div>
<!--
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js"></script>
<script src="bower_components/autosize/dist/autosize.min.js"></script>
<script src="bower_components/highcharts/highcharts.js"></script>
<script src="bower_components/highcharts/highcharts-more.js"></script>
<script src="bower_components/highcharts/modules/exporting.js"></script>
<script src="bower_components/bower-jquery-sparkline/dist/jquery.sparkline.retina.js"></script>
<script src="js/init-sparkline.js"></script>
<script type="text/javascript" src="js/echarts/echarts-all-3.js"></script>
<script src="assets/js/jquery-easy-pie-chart/jquery.easypiechart.js"></script>
<script src="assets/js/horizontal-timeline/js/jquery.mobile.custom.min.js"></script>
<script src="assets/js/horizontal-timeline/js/main.js"></script>
-->

<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('bower_components/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('bower_components/autosize/dist/autosize.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@yield('extra_js_files')
</body>
</html>
