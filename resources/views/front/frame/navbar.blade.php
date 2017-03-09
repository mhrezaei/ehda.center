<div class="cover small">
    <div class="container">
        <ul class="breadcrumbs">
            @if(sizeof($array))

                @foreach($array as $arr)
                    <li><a href="{{ $arr[1] }}"> {{ $arr[0] }} </a></li>
                @endforeach
            @endif
        </ul>
    </div>
</div>