<div class="page-content category">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-center">
                {{ '', $post->spreadMeta() }}
                <div class="faq-item no-collapse">
                    <a href="#" class="q">{{ $post->long_title }}</a>
                    <div class="answer">
                        <p>{!! $post->text !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>