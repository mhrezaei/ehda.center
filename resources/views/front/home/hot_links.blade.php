<div class="container">
    <div class="row mt15 mb20">
        <div class="col-xs-12 col-md-4">
            <a href="#" class="floating-top-40 floating-end-15 link-red">{{ trans('front.more') }}</a>
            @include('front.frame.underlined_heading', [
                'text' => trans('front.hot_news'),
                'color' => 'red',
            ])
            @include('front.home.hot_news')
        </div>
        <div class="col-xs-12 col-md-4">
            @include('front.home.posts_list_view', [
                'title' => trans('front.events'),
                'color' => 'green',
                'posts' => $events,
            ])
        </div>
        <div class="col-xs-12 col-md-4">
            @include('front.home.posts_list_view', [
                'title' => trans('front.ehda_news'),
                'color' => 'blue',
                'posts' => $transplantNews,
            ])
        </div>
    </div>
</div>

