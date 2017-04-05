@if($about)
    {{ '', $about->spreadMeta() }}
<div id="home-about">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-center">
                <p style="text-align: justify;">{{ $about->abstract }}</p>
            </div>
        </div>
    </div>
</div>
@endif