@php
    if(is_array($style)) {
        $styleString = '';
        foreach ($style as $attributeName => $attributeValue) {
            $styleString .= $attributeName .': ' . $attributeValue . ';';
        }
        $style = $styleString;
    }

    if(is_array($class)) {
        $class = implode(' ', $class);
    }
@endphp

<img src="{{ $imgUrl }}" style="max-width: 100%;{{ $style }}"
     @if($class) class="{{ $class }}" @endif
/>