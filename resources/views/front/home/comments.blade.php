@if($comments->count() and setting()->ask('dashboard_comment')->gain() and $commentingPost->canShowComments())
    {{ null, $commentingPost->spreadMeta() }}
    <section id="testimonials">
        <div class="simple-title">{{ $commentingPost->title_shown_on_showing_comments }}</div>
        <div class="testimonials-list">
            @foreach($comments as $comment)
                {{ null , $comment->spreadMeta() }}
                <div class="item">
                    <div class="content">
                        <div class="text">
                            <p>
                                {{ $comment->text }}
                            </p>
                        </div>
                        <div class="person">{{ $comment->creator->full_name }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif