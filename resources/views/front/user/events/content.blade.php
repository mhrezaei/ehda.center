@if(sizeof($events))
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center">
            @foreach($events as $event)
                {{ '', $event->spreadMeta() }}
            <section class="panel event-item">
                <header>
                    <div class="title"> {{ $event->title }} </div>
                    <div class="functions">
                        <div class="label red alt"> {{ trans('front.to') }} {{ echoDate($event->end_time, 'j F Y', 'auto', true) }} </div>
                        <div class="label green alt"> {{ trans('front.all_user_score') }}: {{ pd(floor(user()->sum_receipt_amount / 500000)) }} </div>
                    </div>
                </header>
                <article>
                    {!! $event->text !!}
                </article>
            </section>
            @endforeach
        </div>
    </div>
</div>
@endif