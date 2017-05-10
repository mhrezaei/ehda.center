<ul class="menu">
    <li><a href="{{ url_locale('') }}"> {{ trans('front.home') }} </a></li>
    <li><a href="{{ url_locale('page/about') }}"> {{ trans('front.about') }} </a></li>
    <li> <a href="{{ url_locale('products') }}"> {{ trans('front.products') }} </a>
    <li> <a href="{{ url_locale('news') }}"> {{ trans('front.news') }} </a>
    <li> <a href="{{ url_locale('faqs') }}"> {{ trans('front.faqs') }} </a>
    <li> <a href="{{ url_locale('teammates') }}"> {{ model('Posttype')::findBySlug('teammates')->title }} </a>
    {{--<li class="has-child"> <a href="{{ url_locale('products') }}"> {{ trans('front.products') }} </a>--}}
        {{--<ul class="sub-menu">--}}
            {{--<li><a href="#">پسته</a></li>--}}
            {{--<li><a href="#">بادام</a></li>--}}
            {{--<li><a href="#">آجیل مخلوط</a></li>--}}
            {{--<li><a href="#">سایر</a></li>--}}
        {{--</ul>--}}
    </li>
    <li><a href="{{ url_locale('page/contact') }}"> {{ trans('front.contact_us') }} </a></li>
</ul>