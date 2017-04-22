<div class="container">
    <div class="row">
        <div class="col-sm-8 col-center">
            <section class="panel">
                <header>
                    <div class="title"><span class="icon-comment"></span> {{ $post->title }} </div>
                </header>
                <article>
                    @include('front.frame.widgets.comment_form')
                </article>
            </section>
        </div>
    </div>
</div>