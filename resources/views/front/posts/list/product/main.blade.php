<div class="row">
    @foreach($posts as $post)
        @include($viewFolder . '.item')
    @endforeach
    <div class="row pagination-wrapper mt20 text-center">
        {!! $posts->render() !!}
    </div>
</div>
