@extends('account.shared.main')
@section('breadcrumb',"")
@section('content')
@section('css')
    <link href="https://unpkg.com/video.js@7.0.0/dist/video-js.css" rel="stylesheet">
@endsection
<div style="width: 100%; min-width: 100%; margin: auto; background: #000;">
    <div style="width: 60%; min-width: 60%; margin: auto">
        <video id="my_video_1" class="video-js vjs-default-skin vjs-big-play-centered"  controls preload="auto"
               data-setup='{"fluid": true}'>
        <!-- <source src="{{ \App\Setting::find(1)->streamning_url }}" type="application/x-mpegURL">-->
        </video>
    </div>
</div>
<!--
<ul class="dashboard-genres-pro">
    @php
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    $today = date('l');
    @endphp
    @foreach($days as $day)
        <li class="{{ strtolower($day)==strtolower($today) ? "active" : "" }} {{ $day }} links"><h6 onclick="loadSchedule('{{ $day }}')">{{ $day }}</h6></li>
    @endforeach

</ul>
-->
<div class="clearfix"></div>
<div class="dashboard-container">
    <h4 class="heading-extra-margin-bottom mt-4 mb-5">Programme Schedule</h4>
    <div class="row" id="programme_holder">
        @foreach($progs as $prog)
            <div class="col-6 col-md-6 col-lg-4 col-xl-3">
                <div class="item-listing-container-skrn pb-3">
                    <a href="#" onclick="playVideo('{{ $prog->link }}'); return false;"><img src="{{ asset('programme_image/'.$prog->programme_image) }}" alt="Listing"></a>
                    <h6 class="pt-4 pb-1 text-center">{{ $prog->title }}</h6>
                    <p class="text-center" style="margin: 0; padding: 10px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
                        <?php
                        $out = strlen($prog->description) > 200 ? substr($prog->description,0,200)."..." : $prog->description;
                        ?>
                        {{ $out }}
                    </p>
                    <h6 class="text-center font-bold" style="margin: 0; padding: 0">{{ $prog->day }}</h6>
                    <p class="text-center font-bold" style="margin: 0; padding: 0">Time</p>
                    <h5 class="text-center" style="margin: 0; padding: 0">{{ $prog->from_string }} - {{ $prog->to_string }}</h5>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('js')
    <!--<script src="https://unpkg.com/video.js/dist/video.js"></script>-->
    <!--<script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.21.1/video.min.js"></script>

<script src="https://unpkg.com/browse/@videojs/http-streaming@2.6.0/dist/videojs-http-streaming.min.js"></script>
    <script>
        $(document).ready(function(e){
            /*
            $('.links').removeClass('active');
            var today = '{{ $today }}';
            $('.'+today).addClass('active');
            $.get('{{ route('user.load_programme_ajax') }}?day='+today, function (response) {
                $('#programme_holder').html(response);
            })
            */
        })

        function loadSchedule(today){
            $('.links').removeClass('active');
            $('.'+today).addClass('active');
            $.get('{{ route('user.load_programme_ajax') }}?day='+today, function (response) {
                $('#programme_holder').html(response);
            })
        }
    </script>

    <script>

        var player = videojs('my_video_1',{
            controls: true,
            autoplay: true,
            preload: 'auto'
        });

        player.ready(function() {
            player.play();
        });

        function playVideo(src) {
            player.src({
                src: src
            });

            $("html, body").animate({ scrollTop: 0 }, "slow");
        }

    </script>
@endsection
