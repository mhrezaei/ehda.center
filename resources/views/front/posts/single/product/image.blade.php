<div class="product-images">
    @if(count($post->viewable_album))
        <ul class="slides
        @if($post->isIt('NEW')) ribbon-new @endif
        @if($post->isIt('IN_SALE')) ribbon-sale @endif
                ">
            @foreach($post->viewable_album as $image)
                <li>
                    <a href="{{ $image }}" data-lightbox="product-gallery"><img
                                src="{{ $image }}"></a>
                </li>
            @endforeach
        </ul>
        <ul class="thumbnails" id="product-gallery-thumbnails" data-rtl="false">
            @foreach($post->viewable_album_thumnails as $image)
                <li>
                    <a href="#"><img src="{{ $image }}"></a>
                </li>
            @endforeach
        </ul>
    @else
        <ul class="slides
        @if($post->isIt('NEW')) ribbon-new @endif
        @if($post->isIt('IN_SALE')) ribbon-sale @endif
                ">
            <li>
                <a href="{{ $post->viewable_featured_image }}" data-lightbox="product-gallery"><img
                            src="{{ $post->viewable_featured_image }}"></a>
            </li>
        </ul>
    @endif

</div>