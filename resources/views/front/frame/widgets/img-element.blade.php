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

    if(!$fileExisted) {
        $class .= ' not-found';
    }
@endphp

<img src="{{ $imgUrl }}" style="{{ $style }}"
     @if($class) class="{{ $class }}" @endif
/>