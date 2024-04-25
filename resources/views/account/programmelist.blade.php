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
