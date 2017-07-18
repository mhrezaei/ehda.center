@if($post->canReceiveComments())
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h4>
                    <span class="icon-comment"> </span>
                    {{ $post->title_shown_on_sending_comments }}
                </h4>
            </div>
            <div class="col-xs-12 pb15">
                {!! $post->text  !!}
            </div>


            @include('front.frame.widgets.comment_form', compact('fields'))
            @if($post->show_previous_comments)
                @include($viewFolder . '.previous-comments')
            @endif
        </div>
    </div>
@endif