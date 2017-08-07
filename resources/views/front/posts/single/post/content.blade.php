<div class="col-xs-12">
    <h3 class="post-title">{{ ad($post->title) }}</h3>
    <img class="post-cover" src=" {{ $post->viewable_featured_image }}" alt="">
    <div class="row">
        <div class="col-xs-12 post-text">
            {!! $post->text !!}
        </div>
    </div>
    <small>{{ trans('validation.attributes.publish') }}: {{ ad(echoDate($post->published_at, 'H:i / j F Y')) }}</small>
</div>

{!! $externalBlade or '' !!}

{{-- best command for real post --}}
{{--<small>{{ trans('validation.attributes.publish_date') }}: {{ $post->say('published_at') }}</small>--}}
