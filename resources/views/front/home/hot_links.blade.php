<div class="container">
    <div class="row mt15 mb20">
        <div class="col-xs-12 col-md-4">
            @if($events and $events->count())
                @include('front.home.posts_list_view', [
                    'title' => trans('front.events'),
                    'color' => 'darkGray',
                    'posts' => $events,
                    'moreLink' => route_locale('post.archive', [
                        'postType' => 'event',
                    ]),
                ])
            @endif
        </div>
        <div class="col-xs-12 col-md-4">
            @if($hotNews and $hotNews->count())
                <a href="{{ route_locale('post.archive', [
                    'postType' => 'iran-news',
                    'category' => 'special-news',
                ]) }}"
                   class="floating-top-25 floating-end-15 link-green">{{ trans('front.more') }}</a>
                @include('front.frame.underlined_heading', [
                    'text' => trans('front.special_news'),
                    'color' => 'green',
                ])
                @include('front.home.hot_news')
            @endif
        </div>
        <div class="col-xs-12 col-md-4">
            @if($transplantNews and $transplantNews->count())
                @include('front.home.posts_list_view', [
                    'title' => trans('front.ehda_news'),
                    'color' => 'blue',
                    'posts' => $transplantNews,
                    'moreLink' => route_locale('post.archive', [
                        'postType' => 'iran-news',
                    ]),
                ])
            @endif
        </div>
    </div>
</div>

