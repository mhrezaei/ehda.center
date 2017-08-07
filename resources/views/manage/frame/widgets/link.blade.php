@if(!isset($anchor['attributes']))
    @php
        $anchor['attributes'] = [];
    @endphp
@endif
@if(!isset($anchor['text']))
    @php
        $anchor['text'] = '';
    @endphp
@endif

<a
    @foreach($anchor['attributes'] as $attributeName => $attributeValue)
        {{ $attributeName }}="{{ $attributeValue }}"
    @endforeach
>
    {{ $anchor['text'] }}
</a>