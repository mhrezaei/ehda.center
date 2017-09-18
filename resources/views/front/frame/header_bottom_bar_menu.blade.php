{{--{{ null, $menu = \App\Providers\MenuServiceProvider::getMenuArray() }}--}}
{{ null, $menu = \App\Providers\MenuServiceProvider::getStaticMenuArray() }}

@include('front.frame.register_card_btn_top')
<span class="text-blue f30 mt15 ml20 toggle-menu pull-end"><i class="fa fa-bars"></i></span>
<ul class="list-inline" id="menu-tree" >
    @if($menu and is_array($menu) and count($menu))
        @foreach($menu as $group)
            <li class="has-child">
                <a href="/">{{ $group['title'] }}</a>
                @if(isset($group['children']) and
                    $group['children'] and
                    is_array($group['children']) and
                    count($group['children'])
                )
                    <ul class="bg-blue text-white-deep mega-menu col-xs-12">
                        @foreach($group['children'] as $postType)
                            <ul class="list-unstyled">
                                <h3>
                                    <a @isset($postType['link']) href="{{ $postType['link'] }}" @endisset>
                                        {{ $postType['title'] }}
                                    </a>
                                </h3>
                                @if(isset($postType['children']) and
                                    $postType['children'] and
                                    is_array($postType['children']) and
                                    count($postType['children'])
                                )
                                    @foreach($postType['children'] as $category)
                                        <li>
                                            <a href="{{ $category['link'] or '#' }}">
                                                {{ $category['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    @endif
    {{--<li class="has-child">--}}
    {{--<a href="/">دانستن</a>--}}
    {{--<ul class="bg-primary mega-menu col-xs-12">--}}
    {{--<ul class="list-unstyled">--}}
    {{--<h3>اخبار</h3>--}}
    {{--<li><a href="/">اخبار ایران</a></li>--}}
    {{--<li><a href="/">اخبار جهان</a></li>--}}
    {{--<li><a href="/">اخبار مراکز فراهم&zwnj;آوری</a></li>--}}
    {{--</ul>--}}
    {{--<ul class="list-unstyled">--}}
    {{--<h3>بیشتر بدانیم</h3>--}}
    {{--<li><a href="/">علمی</a></li>--}}
    {{--<li><a href="/">فرهنگی</a></li>--}}
    {{--<li><a href="/">سؤالات رایج</a></li>--}}
    {{--</ul>--}}
    {{--</ul>--}}
    {{--</li>--}}
    {{--<li class="has-child">--}}
    {{--<a href="/">خواستن</a>--}}
    {{--<ul class="bg-primary mega-menu col-xs-12">--}}
    {{--<ul class="list-unstyled">--}}
    {{--<h3>اخبار</h3>--}}
    {{--<li><a href="/">اخبار ایران</a></li>--}}
    {{--<li><a href="/">اخبار جهان</a></li>--}}
    {{--<li><a href="/">اخبار مراکز فراهم&zwnj;آوری</a></li>--}}
    {{--</ul>--}}
    {{--<ul class="list-unstyled">--}}
    {{--<h3>بیشتر بدانیم</h3>--}}
    {{--<li><a href="/">علمی</a></li>--}}
    {{--<li><a href="/">فرهنگی</a></li>--}}
    {{--<li><a href="/">سؤالات رایج</a></li>--}}
    {{--</ul>--}}
    {{--</ul>--}}
    {{--</li>--}}
    {{--<li class="has-child">--}}
    {{--<a href="/">توانستن</a>--}}
    {{--<ul class="bg-primary mega-menu col-xs-12">--}}
    {{--<ul class="list-unstyled">--}}
    {{--<h3>اخبار</h3>--}}
    {{--<li><a href="/">اخبار ایران</a></li>--}}
    {{--<li><a href="/">اخبار جهان</a></li>--}}
    {{--<li><a href="/">اخبار مراکز فراهم&zwnj;آوری</a></li>--}}
    {{--</ul>--}}
    {{--<ul class="list-unstyled">--}}
    {{--<h3>بیشتر بدانیم</h3>--}}
    {{--<li><a href="/">علمی</a></li>--}}
    {{--<li><a href="/">فرهنگی</a></li>--}}
    {{--<li><a href="/">سؤالات رایج</a></li>--}}
    {{--</ul>--}}
    {{--</ul>--}}
    {{--</li>--}}
</ul>
