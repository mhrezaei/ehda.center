@php $post->spreadMeta() @endphp

@if($twoColumns)
    @php $itemClass = 'col-md-6' @endphp
    @php $abstractLimit = 100 @endphp
@else
    @php $itemClass = 'col-md-12' @endphp
    @php $abstractLimit = 300 @endphp
@endif

<div class="{{ $itemClass }} col-xs-12">
    <div class="archive-item full-width pull-start border-1 border-lightGray rounded-corners-5 mb10">
        <div class="media">
            <a class="link-black pull-start" href="{{ $post->direct_url }}">
                <div class="media-start">
                    <img src="{{ $post->viewable_featured_image_thumbnail }}">
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{ ad($post->title) }}</h4>
                    <p class="text-justify">{{ str_limit(ad($post->abstract), $abstractLimit) }}</p>
                    <p class="text-gray">{{ ad(echoDate($post->published_at, 'j F y')) }}</p>
                </div>
            </a>
        </div>
    </div>
</div>
