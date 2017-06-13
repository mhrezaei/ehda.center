@if($posts->count())
    @foreach($posts as $post)
        @include($viewFolder . '.item')
    @endforeach
@endif


