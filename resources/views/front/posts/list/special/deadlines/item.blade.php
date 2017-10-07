@php $post->spreadMeta() @endphp
<div class="col-xs-12 col-sm-6 col-lg-4">
    <div class="row">
        <div class="clearfix text-left col-xs-6 col-lg-12">
            @php $countingClass = $post->increasing ? 'count-up' : 'count-down' @endphp
            <div dir="ltr" class="timer pull-right {{ $countingClass }}"
                 data-minutes="{{ ((is_numeric($post->period_time) and is_int((int) $post->period_time)) ? $post->period_time : 10) }}">
                <span class="hours"></span>
                &nbsp;
                <span class="minutes"></span>
                &nbsp;
                <span class="secconds"></span>
            </div>
            @php
                $fillColor = validateColorCode($post->losed_color_code)
                    ? validateColorCode($post->losed_color_code)
                    : '#3397b9';
                $emptyColor = validateColorCode($post->remained_color_code)
                    ? validateColorCode($post->remained_color_code)
                    : '#88AC2E';
            @endphp
            <div class="circle"
                 data-fill="{&quot;color&quot;: &quot;{{ $emptyColor }}&quot;}"
                 data-empty-fill="{{ $fillColor }}"></div>
        </div>
        <div class="timer-message col-xs-6 col-lg-12">
            <strong>{{ ad($post->title) }}</strong>
            <span>{{ ad($post->abstract) }}</span>
        </div>
    </div>
</div>