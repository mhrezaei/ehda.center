{{ '', $post->spreadMeta() }}
<div class="faq-item no-collapse">
    <a href="#" class="q">{{ $post->long_title }}</a>
    <div class="answer">
        <p>{!! $post->text !!}</p>
    </div>
</div>