<div class="row pt45 pr45 f18 text-justify">
    <div class="col-xs-12">
        @if($profilePost and $profilePost->text)
            <p>{!! $profilePost->text !!}</p>
        @endif
    </div>
    @include('front.user.dashboard.panel_part_buttons')
</div>
