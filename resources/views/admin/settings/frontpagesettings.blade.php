@extends('global.main')
@section('title',"Front Page Settings")
@section('description',"Front Page Settings")
@section('innerTitle',"Front Page Settings")
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

        <div class="row">
            <div class="col-md-6">
                <h4>Home Page Sliding Image</h4>
                <table class="table  table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="homepageSlider">
                    @php
                        $homepageSlider = getFrontPageSettings('sliders') ? getFrontPageSettings('sliders') : []
                    @endphp
                    @forelse($homepageSlider as $slider)
                        <tr>
                            <td><img src="{{ asset('slider_image/'.$slider) }}" class="img-thumbnail" width="150"/></td>
                            <td><input type="hidden" value="{{ $slider }}" name="homeslidingbanner[]"/><a href="#" onclick="$(this).parent().parent().remove(); return false;" class="btn btn-danger btn-sm">Remove</a></td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="3"><button type="button" onclick="return addSlidingBanner()" class="btn btn-primary btn-sm pull-right">Add Image</button> </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-6">
                <h4>App Icon Link</h4>
                <div class="form-group">
                    <label>Apple Store Link</label>
                    <input type="text" value="{{ getFrontPageSettings('apple') }}" name="apple" class="form-control"/>
                </div>
                <div class="form-group" style="margin-top: -30px">
                    <label>Google Play Store Link</label>
                    <input type="text" value="{{ getFrontPageSettings('google') }}" name="google" class="form-control"/>
                </div>
                <div class="form-group"  style="margin-top: -30px">
                    <label>Apple TV Link</label>
                    <input type="text" value="{{ getFrontPageSettings('appletv') }}"  name="appletv" class="form-control"/>
                </div>
                <div class="form-group"  style="margin-top: -30px">
                    <label>Amazon TV Link</label>
                    <input type="text" value="{{ getFrontPageSettings('amazontv') }}" name="amazontv" class="form-control"/>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Home Page Text and Image</h3>
                <table class="table  table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Text</th>
                        <th>Display</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="homepagetext">
                    @php
                        $homepagetextentry = getFrontPageSettings('homepagetextentry') ? getFrontPageSettings('homepagetextentry') : []
                    @endphp

                    @forelse( $homepagetextentry as $entry)
                        <tr>
                            <input type="hidden" name="homepagetextentry[logo][]" value="{{ $entry['logo'] }}"/>
                            <th><img src="{{ asset('home_text_entry/'.$entry['logo']) }}" class="img-thumbnail" width="150"/></th>
                            <th><textarea  class="form-control" name="homepagetextentry[title][]">{{ $entry['title'] }}</textarea></th>
                            <th><textarea  class="form-control" name="homepagetextentry[text][]">{{ $entry['text'] }}</textarea></th>
                            <th><select class="form-control" name="homepagetextentry[display][]"><option {{ $entry['display']=="1" ? 'selected' : "" }} value="1">Image  Left Text Right</option><option {{ $entry['display']=="2" ? 'selected' : "" }} value="2">Image  Right Text Left</option></select></th>
                            <th><a href="#" onclick="$(this).parent().parent().remove(); return false;" class="btn btn-danger btn-sm">Remove</a></th>
                        </tr>
                        @empty
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5"><button type="button" onclick="return addhomepageTextEntry()" class="btn btn-primary btn-sm pull-right">Add Entry</button> </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>Social Media Links</h4>
                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text"  value="{{ getFrontPageSettings('facebook') }}" name="facebook" class="form-control"/>
                </div>
                <div class="form-group" style="margin-top: -30px">
                    <label>Instagram</label>
                    <input type="text" value="{{ getFrontPageSettings('instagram') }}" name="instagram" class="form-control"/>
                </div>
                <div class="form-group"  style="margin-top: -30px">
                    <label>Twitter</label>
                    <input type="text" value="{{ getFrontPageSettings('telegram') }}" name="twitter" class="form-control"/>
                </div>
                <div class="form-group"  style="margin-top: -30px">
                    <label>Telegram</label>
                    <input type="text" value="{{ getFrontPageSettings('telegram') }}" name="telegram" class="form-control"/>
                </div>

                <div class="form-group">
                    <label>Copyright</label>
                    <textarea name="copyright" placeholder="Copyright" class="form-control">{{ getFrontPageSettings('copyright') }}</textarea>
                </div>
            </div>

        </div>

        <button type="submit"  id="save" class="btn btn-success btn-sm">SAVE ALL</button>
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

    <script>
        function addSlidingBanner()
        {
            var html = '<tr><th><input type="file" style="margin-bottom:-10px" name="homeslidingbanner[]" class="form-control homeslidingbanner"/> </th><th><a href="#" onclick="$(this).parent().parent().remove(); return false;" class="btn btn-danger btn-sm">Remove</a></th></tr>';
            $('#homepageSlider').append(html);
        }

        function addhomepageTextEntry()
        {
            var html = '<tr>'+
                '<th><input type="file" style="margin-bottom:-10px" name="homepagetextentry[logo][]" class="form-control homeslidingbanner"/></th>' +
                '<th><textarea  class="form-control" name="homepagetextentry[title][]"></textarea></th>' +
                '<th><textarea  class="form-control" name="homepagetextentry[text][]"></textarea></th>' +
                '<th><select class="form-control" name="homepagetextentry[display][]"><option value="1">Image  Left Text Right</option><option value="2">Image  Right Text Left</option></select></th>' +
                '<th><a href="#" onclick="$(this).parent().parent().remove(); return false;" class="btn btn-danger btn-sm">Remove</a></th>' +
                '</tr>';
            $('#homepagetext').append(html);
        }

    </script>
@endsection
