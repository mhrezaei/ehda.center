<div class="col-xs-12">
    <h3 class="post-title">{{ $post->title }}</h3>
    <img class="post-cover" src=" {{ $post->viewable_featured_image }}" alt="">
    {!! $post->text !!}
    <small>{{ trans('validation.attributes.publish') }}: {{ ad(echoDate($post->published_at, 'H:i / j F Y')) }}</small>
</div>

{!! $externalBlade or '' !!}

{{-- best command for real post --}}
{{--<small>{{ trans('validation.attributes.publish_date') }}: {{ $post->say('published_at') }}</small>--}}
