<div class="col-xs-12">
    <h3 class="post-title">{{ ad($post->title) }}</h3>
    @php
        $featuredImageUrl = \App\Providers\UploadServiceProvider::changeFileUrlVersion($post->preview_image, 'single');
    @endphp
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6 text-center">
                    @if($featuredImageUrl)
                        <img class="post-cover" src=" {{ url($featuredImageUrl) }}" alt="">
                    @endif
                </div>
                <div class="col-xs-6">
                    @php $purchaseFormText = \App\Providers\PostsServiceProvider::smartFindPost('purchase-form') @endphp
                    @if($purchaseFormText->exists)
                        <div class="row">
                            <div class="col-xs-12">
                                {!! $purchaseFormText->text !!}
                            </div>
                        </div>
                    @endif

                    <span class="text-green">
                        {{ trans('cart.price') }}:
                        {{ ad(number_format($post->price)) }} {{ trans('front.toman') }}
                    </span>
                    &nbsp;&nbsp;

                    @if($post->guidance_file)
                        @php
                            $file = \App\Providers\UploadServiceProvider::findFileByPathname($post->guidance_file)
                        @endphp
                        @if($file)
                            <a class="btn btn-primary"
                               href="{{ route('file.download', ['hashid' => $file->hashid]) }}">
                                {{ trans('validation.attributes.hint') }}
                            </a>
                        @endif
                    @endif
                    &nbsp;&nbsp;

                    <button type="button" class="btn btn-success" id="btn-purchase">
                        {{ trans('cart.purchase') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include($viewFolder . '.purchase-form')
    <div class="row">
        <div class="col-xs-12 post-text">
            {!! $post->text !!}
        </div>
    </div>
    @include($viewFolder . '.payment-result')
    @include($viewFolder . '.files')
    {{--    <small>{{ trans('validation.attributes.publish') }}: {{ ad(echoDate($post->published_at, 'H:i / j F Y')) }}</small>--}}
</div>

{!! $externalBlade or '' !!}


{{-- @todo best command for real post --}}
{{--<small>{{ trans('validation.attributes.publish_date') }}: {{ $post->say('published_at') }}</small>--}}