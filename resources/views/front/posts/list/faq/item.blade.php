{{ '', $post->spreadMeta() }}
<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading{{ $post->id }}">
        <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
               href="#collapse{{ $post->id }}" aria-expanded="false" aria-controls="collapse{{ $post->id }}">
                {{ ad($post->title) }}
            </a>
        </h4>
    </div>
    <div id="collapse{{ $post->id }}" class="panel-collapse collapse" role="tabpanel"
         aria-labelledby="heading{{ $post->id }}">
        <div class="panel-body">
            {!! $post->text !!}
        </div>
    </div>
</div>