<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-center">
                @if($news)
                    @foreach($news as $new)
                        {{ '', $new->spreadMeta() }}
                <div class="blog-item style-1">
                    <div class="thumbnail"> <img src="{{ url($new->featured_image) }}"> </div>
                    <div class="content"> <a href="{{ url_locale('page/' . $new->id) }}"><h3 class="title"> {{ $new->title }} </h3></a>
                        <div class="excerpt">
                            <p>
                                {{ $new->abstract }}
                            </p>
                        </div>
                        <div class="action">
                            <a href="{{ url_locale('page/' . $new->id) }}" class="more">{{ trans('front.read_more') }}
                                <span class="icon-angle-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
                    @endforeach
                @endif

                {{--<div class="pagination-wrapper mt20">--}}
                    {{--<ul class="pagination">--}}
                        {{--<li><a href="#">«</a></li>--}}
                        {{--<li class="active"><span>۱</span></li>--}}
                        {{--<li><a href="#">۲</a></li>--}}
                        {{--<li><a href="#">۳</a></li>--}}
                        {{--<li><a href="#">۴</a></li>--}}
                        {{--<li><a href="#">۵</a></li>--}}
                        {{--<li><a href="#">»</a></li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
</div>