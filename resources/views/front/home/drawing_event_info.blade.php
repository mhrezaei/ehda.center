@if($event)
    <div class="row">
        <div class="col-xs-12">
            <h3>{{ $event->title }}</h3>
            <p class="text-black f12">
                {{ trans('front.from') }}
                {{ echoDate($event->starts_at, 'j F Y') }}
                {{ trans('front.to') }}
                {{ echoDate($event->ends_at, 'j F Y') }}
            </p>
        </div>
    </div>
@endif
