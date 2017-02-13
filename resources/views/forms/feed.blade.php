@if(!isset($condition) or $condition)
    <div class="form-feed alert" style="display:none">
        {{ $feed_wait or trans('forms.feed.wait') }}
    </div>
    <div class="d-n hide">
        <span class=" form-feed-wait" style="color: black;">
            <div style="width: 100%; text-align: center;">
                {{  $feed_wait or trans('forms.feed.wait')  }}
                {{--<br>--}}
                {{--<img src="{{ url('assets/site/images/64.gif') }}">--}}
            </div>
        </span>
        <span class=" form-feed-error">{{ $feed_error or trans('forms.feed.error') }}</span>
        <span class=" form-feed-ok">{{ $feed_ok or trans('forms.feed.done') }}</span>
    </div>
@endif