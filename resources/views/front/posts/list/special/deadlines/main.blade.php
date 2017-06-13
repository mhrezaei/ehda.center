@if($posts->count())
    @foreach($posts as $key => $post)
        @if(($key % 3) == 0)
            <div class="row">
                @endif
                @include($viewFolder. '.item')
                @if((($key % 3) == 2) or ($key == ($posts->count() - 1)))
            </div>
        @endif
    @endforeach
@endif
{{--<div class="col-xs-12 col-sm-6 col-lg-3">--}}
{{--<div class="row">--}}
{{--<div class="clearfix text-left col-xs-6 col-lg-12 count-down">--}}
{{--<div dir="ltr" class="timer pull-right" data-minutes="70">--}}
{{--<span class="hours"></span><span class="minutes"></span><span--}}
{{--class="secconds"></span>--}}
{{--</div>--}}
{{--<div class="circle" data-fill="{&quot;color&quot;: &quot;#1c3482&quot;}"--}}
{{--data-empty-fill="#3ab637"></div>--}}
{{--</div>--}}
{{--<div class="timer-message col-xs-6 col-lg-12">--}}
{{--<strong>در هر ۷۰ دقیقه</strong>--}}
{{--<span>یک نفر در ایران با مرگ مغزی جان خود را از دست می&zwnj;دهد.</span>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="col-xs-12 col-sm-6 col-lg-3">--}}
{{--<div class="row">--}}
{{--<div class="clearfix text-left col-xs-6 col-lg-12 count-down">--}}
{{--<div dir="ltr" class="timer pull-right" data-minutes="120">--}}
{{--<span class="hours"></span><span class="minutes"></span><span--}}
{{--class="secconds"></span>--}}
{{--</div>--}}
{{--<div class="circle" data-fill="{&quot;color&quot;: &quot;#1c3482&quot;}"--}}
{{--data-empty-fill="#3ab637"></div>--}}
{{--</div>--}}
{{--<div class="timer-message col-xs-6 col-lg-12">--}}
{{--<strong>در هر ۲ ساعت</strong>--}}
{{--<span>یک بیمار نیازمند به پیوند، جان خود را از دست می&zwnj;دهد.</span>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="col-xs-12 col-sm-6 col-lg-3">--}}
{{--<div class="row">--}}
{{--<div class="clearfix text-left col-xs-6 col-lg-12 count-down">--}}
{{--<div dir="ltr" class="timer pull-right" data-minutes="720">--}}
{{--<span class="hours"></span><span class="minutes"></span><span--}}
{{--class="secconds"></span>--}}
{{--</div>--}}
{{--<div class="circle" data-fill="{&quot;color&quot;: &quot;#3ab637&quot;}"--}}
{{--data-empty-fill="#ccc"></div>--}}
{{--</div>--}}
{{--<div class="timer-message col-xs-6 col-lg-12">--}}
{{--<strong>در هر ۱۲ ساعت</strong>--}}
{{--<span>یک بیمار موفق به دریافت عضو حیاتی می&zwnj;شود و به زندگی بازمی&zwnj;گردد.</span>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
