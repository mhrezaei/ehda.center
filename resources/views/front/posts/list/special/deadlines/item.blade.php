{{ null, $post->spreadMeta() }}
<div class="col-xs-12 col-sm-6 col-lg-4">
    <div class="row">
        <div class="clearfix text-left col-xs-6 col-lg-12">
            {{ null, $countingClass = $post->increasing ? 'count-up' : 'count-down' }}
            <div dir="ltr" class="timer pull-right {{ $countingClass }}"
                 data-minutes="{{ ((is_numeric($post->period_time) and is_int((int) $post->period_time)) ? $post->period_time : 10) }}">
                <span class="hours"></span><span class="minutes"></span><span
                        class="secconds"></span>
            </div>
            {{ null, $fillColor = validateColorCode($post->losed_color_code) ?validateColorCode($post->losed_color_code) : '#1c3482' }}
            {{ null, $emptyColor = validateColorCode($post->remained_color_code) ?validateColorCode($post->remained_color_code) : '#3ab637' }}
            <div class="circle"
                 data-fill="{&quot;color&quot;: &quot;{{ $emptyColor }}&quot;}"
                 data-empty-fill="{{ $fillColor }}"></div>
        </div>
        <div class="timer-message col-xs-6 col-lg-12">
            <strong>{{ $post->title }}</strong>
            <span>{{ $post->abstract }}</span>
        </div>
    </div>
</div>