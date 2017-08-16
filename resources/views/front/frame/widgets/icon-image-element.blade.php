@php
    $defaultSize = 100;

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

    switch ($fileType) {
        case "video":
            $imageName = 'file-video-o.svg';
            break;
        case "audio":
            $imageName = 'file-audio-o.svg';
            break;
        case "text":
        case "application":
        case "docs":
            $imageName = 'file-text-o.svg';
            break;
    }

    $imageUrl = url('assets/images/template/' . $imageName)
@endphp

@section('html_header')
    @include('front.frame.widgets.icon-image-element-styles')
@append
@section('head')
    @include('front.frame.widgets.icon-image-element-styles')
@append

<div class="icon-image-wrapper {{ $class }}"
     style="height: {{ $defaultSize }}px;width: {{ $defaultSize }}px; {{ $style }}">
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAA/UlEQVR4nO3RMQ0AMAzAsPIn3d5DsBw2gkiZJWV+B/AyJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQGENiDIkxJMaQmAP4K6zWNUjE4wAAAABJRU5ErkJggg==">
</div>