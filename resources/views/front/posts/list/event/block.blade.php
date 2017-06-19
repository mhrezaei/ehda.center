@if(sizeof($posts))
    @foreach($posts as $event)
        @include($viewFolder . '.item')
    @endforeach

    @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination-wrapper">
            {!! $posts->render() !!}
        </div>
    @endif
@endif
