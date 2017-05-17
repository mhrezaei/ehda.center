<div class="col-sm-8">
    @if($event)
        {{ '', $event->spreadMeta() }}
        <div class="event-main"><img src="{{ url($event->featured_image) }}"></div>
    @endif
</div>
