@php
    $defaultOptions = [
        'buttonColor' => 'success',
        'buttonText' => '',
        'buttonClass' => '',
        'buttonIcon' => 'font',
        'buttonLink' => '#',
        'dataAttributes' => null,
        'otherAttributes' => null,
    ];

    if(!isset($buttonOptions)) {
        $buttonOptions = [];
    }

    $buttonOptions = array_normalize($buttonOptions, $defaultOptions);
@endphp
<div class="col-sm-6 col-xs-12 mb10">
    <div class="row">
        <div class="col-xs-10">
            <a href="{{ $buttonOptions['buttonLink'] }}"
               class="btn btn-with-image btn-block btn-with-image-{{ $buttonOptions['buttonIcon'] }}
                       btn-{{ $buttonOptions['buttonColor'] }} {{ $buttonOptions['buttonClass'] }}"
                @if(isset($buttonOptions['dataAttributes']) and is_array($buttonOptions['dataAttributes']))
                    @foreach($buttonOptions['dataAttributes'] as $attributeName => $attributeValue)
                        data-{{ $attributeName }}="{{ $attributeValue }}"
                    @endforeach
                @endif
                @if(isset($buttonOptions['otherAttributes']) and is_array($buttonOptions['dataAttributes']))
                    @foreach($buttonOptions['otherAttributes'] as $attributeName => $attributeValue)
                        {{ $attributeName }}="{{ $attributeValue }}"
                    @endforeach
                @endif
            >
                {{ $buttonOptions['buttonText'] }}
            </a>
        </div>
    </div>
</div>