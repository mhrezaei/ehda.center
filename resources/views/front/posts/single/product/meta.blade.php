<div class="product-meta">
    <div class="row">
        <div class="col-sm-2">
            <div class="meta-title"> {{ trans('front.categories') }}:</div>
        </div>
        <div class="col-sm-10">
            <div class="meta-value">
                <ul class="categories">
                    @foreach($post->categories as $category)
                        <li><a href="#"> {{ $category->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    {{--<div class="row">--}}
    {{--<div class="col-sm-2">--}}
    {{--<div class="meta-title"> {{ trans('posts.features.tags') }}:</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-10">--}}
    {{--<div class="meta-value">--}}
    {{--<ul class="tags">--}}
    {{--<li><a href="#"> پسته </a></li>--}}
    {{--<li><a href="#"> پسته‌ی خام </a></li>--}}
    {{--<li><a href="#"> دامغان </a></li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row">--}}
    {{--<div class="col-sm-2">--}}
    {{--<div class="meta-title"> کد محصول</div>--}}
    {{--</div>--}}
    {{--<div class="col-sm-10">--}}
    {{--<div class="meta-value">--}}
    {{--<div class="code"> ۲۴۳۵۴۶۲</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
</div>