<div class="col-xs-12">
    <h3 class="post-title">{{ ad($post->title) }}</h3>
    @php
        $featuredImageUrl = \App\Providers\UploadServiceProvider::changeFileUrlVersion($post->viewable_featured_image, 'single');
    @endphp
    <div class="row">
        <div class="col-xs-12 text-center">
            <img class="post-cover" src=" {{ $featuredImageUrl }}" alt="">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 post-text">
            {!! $post->text !!}
        </div>
    </div>
    @include($viewFolder . '.files')
    @include($viewFolder . '.purchase-form')
    <small>{{ trans('validation.attributes.publish') }}: {{ ad(echoDate($post->published_at, 'H:i / j F Y')) }}</small>
</div>

{!! $externalBlade or '' !!}


{{-- @todo best command for real post --}}
{{--<small>{{ trans('validation.attributes.publish_date') }}: {{ $post->say('published_at') }}</small>--}}