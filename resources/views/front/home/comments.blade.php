<section id="testimonials">
    <div class="simple-title"> مشتریان در موردمان چه می‌گویند؟</div>
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