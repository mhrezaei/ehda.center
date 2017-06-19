{{ '', $post->spreadMeta() }}
<section class="panel event-item">
    <header>
        <div class="title"> {{ $post->title }} </div>
        <div class="text-gray alt mt10 f16">
            <span class="f12">
                <i class="fa fa-clock-o"></i>
                {{ trans('front.from') }}
                {{ echoDate($post->starts_at, 'j F Y', 'auto', true) }}
                {{ trans('front.to') }}
                {{ echoDate($post->ends_at, 'j F Y', 'auto', true) }}
            </span>
            @if(user()->exists and \Carbon\Carbon::parse($post->starts_at)->lte(\Carbon\Carbon::now()))
                {{ null, $color = \Carbon\Carbon::parse($post->ends_at)->lt(\Carbon\Carbon::now()) ? 'gray' : 'green' }}
                <div class="label {{ $color }} alt pull-left">
                    {{ trans('front.all_user_score') }} {{ pd(PostsServiceProvider::getUserPointOfEvent($post)) }}
                </div>
            @endif
        </div>
    </header>
    <article>
        {!! $post->text !!}
        <img src="{{ url($post->featured_image) }}">
        <div class="text-center pt10">
            @if($post->winners_list_post)
                <a href="{{$post->winners_list_post}}" download="">
                    <i class="fa fa-list"></i>
                    {{trans('cart.drawing_winners')}}
                </a>
            @endif
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @if($post->drawing_video)
                <a href="{{$post->drawing_video}}" download="">
                    <i class="fa fa-video-camera"></i>
                    {{trans('cart.drawing_video')}}
                </a>
            @endif
        </div>
    </article>
</section>