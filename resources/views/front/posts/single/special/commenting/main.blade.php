{{ null , $post->spreadMeta() }}
<section class="panel">
    <header>
        <div class="title">
            <span class="icon-comment"> </span>
            {{ $post->title_shown_on_sending_comments }}
        </div>
    </header>
    <article>
        <div class="col-xs-12 pb15">
            {!! $post->text  !!}
        </div>
        @include('front.frame.widgets.comment_form')
        @if($post->show_previous_comments)
            @include($viewFolder . '.previous-comments')
        @endif
    </article>
</section>