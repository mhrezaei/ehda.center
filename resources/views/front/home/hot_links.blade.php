<div class="container">
    <div class="row mt15 mb20">
        <div class="col-xs-12 col-md-4">
            @if($hotNews and $hotNews->count())
                <a href="#" class="floating-top-25 floating-end-15 link-darkGray">{{ trans('front.more') }}</a>
                @include('front.frame.underlined_heading', [
                    'text' => trans('front.special_news'),
                    'color' => 'green',
                ])
                @include('front.home.hot_news')
            @endif
        </div>
        <div class="col-xs-12 col-md-4">
            @if($events and $events->count())
                @include('front.home.posts_list_view', [
                    'title' => trans('front.events'),
                    'color' => 'darkGray',
                    'posts' => $events,
                ])
            @endif
        </div>
        <div class="col-xs-12 col-md-4">
            @if($transplantNews and $transplantNews->count())
                @include('front.home.posts_list_view', [
                    'title' => trans('front.ehda_news'),
                    'color' => 'blue',
                    'posts' => $transplantNews,
                ])
            @endif
        </div>
    </div>
</div>

