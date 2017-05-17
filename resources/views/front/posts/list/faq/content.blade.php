
                @foreach($posts as $post)
                    {{ '', $post->spreadMeta() }}
                    <div class="faq-item">
                        <a href="#" class="q">{{ $post->long_title }}</a>
                        <div class="answer">
                            <p>{!! $post->text !!}</p>
                        </div>
                    </div>
                @endforeach