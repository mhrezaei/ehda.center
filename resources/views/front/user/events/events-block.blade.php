@if(sizeof($events))
    @foreach($events as $event)
        @include('front.user.events.event-item')
    @endforeach

    @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="pagination-wrapper">
            {!! $events->render() !!}
        </div>
    @endif
@endif
