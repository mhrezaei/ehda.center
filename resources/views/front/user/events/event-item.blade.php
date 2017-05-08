{{ '', $event->spreadMeta() }}
<section class="panel event-item">
    <header>
        <div class="title"> {{ $event->title }} </div>
        <div class="text-gray alt mt10 f16">
            <span class="f12">
                <i class="fa fa-clock-o"></i>
                {{ trans('front.from') }}
                {{ echoDate($event->start_time, 'j F Y', 'auto', true) }}
                {{ trans('front.to') }}
                {{ echoDate($event->end_time, 'j F Y', 'auto', true) }}
            </span>
            @if(\Carbon\Carbon::parse($event->starts_at)->lte(\Carbon\Carbon::now()))
                {{ null, $color = \Carbon\Carbon::parse($event->ends_at)->lt(\Carbon\Carbon::now()) ? 'gray' : 'green' }}
                <div class="label {{ $color }} alt pull-left">
                    {{ trans('front.all_user_score') }} {{ pd(PostsServiceProvider::getUserPointOfEvent($event)) }}
                </div>
            @endif
        </div>
    </header>
    <article>
        {!! $event->text !!}
        <img src="{{ url($event->featured_image) }}">
        <div class="text-center pt10">
            {{--            @if($event->winners_list_post)--}}
            <a href="{{$event->winners_list_post}}" download="">
                <i class="fa fa-list"></i>
                {{trans('cart.drawing_winners')}}
            </a>
            {{--@endif--}}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            {{--@if($event->drawing_video)--}}
            <a href="{{$event->drawing_video}}" download="">
                <i class="fa fa-video-camera"></i>
                {{trans('cart.drawing_video')}}
            </a>
            {{--@endif--}}
        </div>
    </article>
</section>