@if(!isset($condition) or $condition)
    <a
            id="{{ $id or '' }}"
            type="{{ $type or 'button' }}"
            name="_{{ $type or 'button' }}"
            value="{{ $value or '' }}"
            class="btn btn-{{$shape or 'default'}} {{$class or ''}} "
            @if(isset($link))
                @if(str_contains($link , '(') or str_contains($link , ')'))
                    onclick="{{$link}}"
                @else
                    href="{{ url($link) }}"
                @endif
            @endif
            {{ $extra or '' }}
    >
        {{$label or ''}}
    </a>
@endif