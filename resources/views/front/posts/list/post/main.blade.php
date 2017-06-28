<div class="row archive">
    @foreach($posts as $post)
        @include($viewFolder . '.item')
    @endforeach
</div>