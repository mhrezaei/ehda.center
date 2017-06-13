@if(isset($text) and $text)
    {{ null, $class = (isset($color) and $color) ? ("text-$color border-bottom-$color") : '' }}
    <h3 class="underlined-heading">
        <span class="{{ $class }}">{{ $text }}</span>
    </h3>
@endif
