<div class="col-xs-12">
    @php
        $featuredImageUrl = \App\Providers\UploadServiceProvider::changeFileUrlVersion($post->preview_image, 'single');
    @endphp
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4 col-xs-12 text-center pull-end">
                    @if($featuredImageUrl)
                        <img class="post-cover" src=" {{ url($featuredImageUrl) }}" alt="">
                    @endif
                </div>
                <div class="col-md-8 col-xs-12 pull-end">
                    <h3 class="post-title mt0">{{ ad($post->title) }}</h3>
                    <div class="row">
                        <div class="col-xs-12 post-text text-justify border-bottom-1 border-bottom-darkGray mb20">
                            {!! $post->text !!}
                        </div>
                        @php
                            $purchaseFormText = \App\Providers\PostsServiceProvider::smartFindPost('purchase-form')
                                ->in(getLocale());
                        @endphp
                        @if($purchaseFormText->exists)
                            <div class="col-xs-12">
                                {!! $purchaseFormText->text !!}
                            </div>
                        @endif
                        <div class="col-sm-6 col-xs-12 pull-end mt10">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
                                    @if($post->guidance_file)
                                        @php
                                            $file = \App\Providers\UploadServiceProvider::findFileByPathname($post->guidance_file)
                                        @endphp
                                        @if($file)
                                            <a class="btn btn-primary btn-block"
                                               href="{{ route('file.download', ['hashid' => $file->hashid]) }}">
                                                {{ trans('validation.attributes.hint') }}
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12 text-center pull-end mt10">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
                                    <button type="button" class="btn btn-success btn-block" id="btn-purchase">
                                        {{ trans('cart.purchase') }}
                                    </button>
                                    <span class="text-green">
                                        {{ trans('forms.general.min') }}:
                                        {{ ad(number_format($post->price)) }} {{ trans('front.toman') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include($viewFolder . '.purchase-form')
                </div>
            </div>
        </div>
    </div>
    @include($viewFolder . '.payment-result')
    @include($viewFolder . '.files')
    {{--    <small>{{ trans('validation.attributes.publish') }}: {{ ad(echoDate($post->published_at, 'H:i / j F Y')) }}</small>--}}
</div>

{!! $externalBlade or '' !!}


{{-- @todo best command for real post --}}
{{--<small>{{ trans('validation.attributes.publish_date') }}: {{ $post->say('published_at') }}</small>--}}