<div class="cover small">
    <div class="container">
        <ul class="breadcrumbs">
            @if(sizeof($array))

                @foreach($array as $arr)
                    <li>
                        @if(isset($arr[1]) and strlen(trim($arr[1])))
                            <a href="{{ $arr[1] }}">
                                {{ $arr[0] }}
                            </a>
                        @else
                            <span>
                                {{ $arr[0] }}
                            </span>
                        @endif
                    </li>
                @endforeach
            @endif
        </ul>
        @if(isset($title))
            <div class="title"> {{ $title or '' }} </div>
        @endif
    </div>
</div>