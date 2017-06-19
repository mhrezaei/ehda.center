<div class="container">
    <div class="row mt15 mb20">
        <div class="col-xs-12 col-md-4">
            <a href="#" class="floating-top-20 floating-end-15 link-red">بیشتر</a>
            @include('front.frame.underlined_heading', [
                'text' => 'اخبار داغ',
                'color' => 'red',
            ])
            @include('front.home.hot_news')
        </div>
        <div class="col-xs-12 col-md-4">
            @include('front.home.posts_list_view', [
                'title' => 'رویدادها',
                'color' => 'green'
            ])
        </div>
        <div class="col-xs-12 col-md-4">
            @include('front.home.posts_list_view', [
                'title' => 'اخبار اهدا',
                'color' => 'blue'
            ])
        </div>
    </div>
</div>

