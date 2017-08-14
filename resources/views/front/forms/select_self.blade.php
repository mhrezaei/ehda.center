{{--@php--}}
{{--$option = $options[1];--}}
{{--$value_field = isset($value_field) ? $value_field : 'id';--}}
{{--$optionValue = isset($option[$value_field]) ? $option[$value_field] : '';--}}

{{--$caption_field = isset($caption_field) ? $value_field : 'title';--}}
{{--$captionValue = $option[$caption_field];--}}
{{--dd($captionValue);--}}
{{--@endphp--}}
@if(!isset($condition) or $condition)

    @if(isset($top_label))
        <label for="{{$name}}" class="control-label text-gray {{$top_label_class or ''}}">{{ $top_label }}...</label>
    @endif

    <select
            id="{{$id or ''}}"
            name="{{$name}}" value="{{$value or ''}}"
            class="form-control {{$class or ''}}"
            placeholder="{{$placeholder or ''}}"
            data-size="{{$size or 5}}"
            data-live-search="{{$search or false}}"
            data-live-search-placeholder="{{$search_placeholder or trans('forms.button.search')}}..."
            onchange="{{$on_change or ''}}"
            @if(isset($dataAttributes) and is_array($dataAttributes))
                @foreach($dataAttributes as $attributeName => $attributeValue)
                    data-{{ $attributeName }}="{{ $attributeValue }}"
                @endforeach
            @endif
            @if(isset($otherAttributes) and is_array($dataAttributes))
                @foreach($otherAttributes as $attributeName => $attributeValue)
                    {{ $attributeName }}="{{ $attributeValue }}"
                @endforeach
            @endif
            {{$extra or ''}}
    >
        @if(isset($blank_value) and $blank_value!='NO')
            <option value="{{$blank_value}}"
                    @if(!isset($value) or $value==$blank_value)
                    selected
                    @endif
            >{{ $blank_label or '' }}</option>
        @endif
        @foreach($options as $option)
            @php
                $value_field = isset($value_field) ? $value_field : 'id';
                $optionValue = isset($option[$value_field]) ? $option[$value_field] : '';

			    $caption_field = isset($caption_field) ? $caption_field : 'title';
                $captionValue = $option[$caption_field];
            @endphp

            <option value="{{ $optionValue }}"
                    @if(isset($value) and $value == $optionValue)
                    selected
                    @endif
            >
                {{ $captionValue }}
            </option>
        @endforeach
    </select>

    @include("forms.js" , [
        'commands' => [
            isset($on_change) ? [$on_change] : [],
        ]
    ])
@endif
