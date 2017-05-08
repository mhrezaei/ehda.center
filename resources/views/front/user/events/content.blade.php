<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center pt15">
            <article class="yt-accordion">
                <h4 class="yt-accordion-header first default-active">
                    <i class="shop-icon shop-icon-accordion"></i>
                    <span class="text-violet">{{ trans('front.running_events') }}</span>
                </h4>
                <div class="yt-accordion-panel auto-height">
                    @include('front.user.events.events-block' , [
                        'events' => $runningEvents
                    ])
                </div>
                <h4 class="yt-accordion-header default-active">
                    <i class="shop-icon shop-icon-accordion"></i>
                    <span class="text-violet">{{ trans('front.soon') }}</span>
                </h4>
                <div class="yt-accordion-panel yt-lazy-load auto-height" data-url="{{ url(getLocale() . '/user/events/waiting') }}">
                </div>
                <h4 class="yt-accordion-header last">
                    <i class="shop-icon shop-icon-accordion"></i>
                    <span class="text-violet">{{ trans('front.expired_events') }}</span>
                </h4>
                <div class="yt-accordion-panel yt-lazy-load auto-height" data-url="{{ url(getLocale() . '/user/events/expired') }}">
                </div>
            </article>
        </div>
    </div>
</div>

<script>
    var acoordionIcons = {
        header: "ui-icon-plusthick",
        activeHeader: "ui-icon-minusthick"
    };

</script>
{{-- @TODO: rate_point should be set dynamically. Currently, was hardcode! --}}