<li class="yt-gallery-item">
    <div>
        <a href="#">
            {{ null, $thumbnailClass = ($i % 7 == 3) ? 'border-gold' : 'border-silver' }}
            <img class="yt-gallery-item-img {{ $thumbnailClass }} lazy"
                 data-original="http://lorempixel.com/300/300/fashion/?a={{ str_random(5) }}"
                 alt="">
            <div class="yt-gallery-item-text">نام و نام خانوادگی</div>
        </a>
    </div>
</li>