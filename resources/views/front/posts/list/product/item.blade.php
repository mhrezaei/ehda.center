@php $post->spreadMeta() @endphp
@php $abstractLimit = 300 @endphp

<div class="col-xs-12 border-1 border-lightGray rounded-corners-5 mb10" style="overflow: hidden">
    <div class="row">
        <div class="media">
                <div class="media-start">
                    <img src="{{ $post->viewable_featured_image_thumbnail }}">
                </div>
                <div class="media-body pl5 pr5">
                    <h4 class="media-heading">{{ ad($post->title) }}</h4>
                    <p class="text-justify">{{ str_limit(ad($post->abstract), $abstractLimit) }}</p>
                    <br/><br/>
                    <p class="text-justify text-green">
                        {{ trans('front.price') }}
                        :
                        {{ ad(number_format($post->price)) }}
                        {{ trans('front.rials') }}
                    </p>
                </div>
                <div class="media-end text-center pr15 pl15 pt70 border-right-1 border-right-lightGray">
                    <a class="btn btn-success mb25 btn-block" href="{{ $post->direct_url }}">
                        {{ trans('cart.purchase') }}
                    </a>
                    @if($post->guidance_file)
                        @php
                            $file = \App\Providers\UploadServiceProvider::findFileByPathname($post->guidance_file)
                        @endphp
                        @if($file)
                            <a class="btn btn-primary mb25 btn-block"
                                    href="{{ route('file.download', ['hashid' => $file->hashid]) }}">
                                {{ trans('validation.attributes.hint') }}
                            </a>
                        @endif
                    @endif
                </div>
        </div>
    </div>
</div>
