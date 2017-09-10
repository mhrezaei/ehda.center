@if(!isset($condition) or $condition)
    @if(isset($top_label))
        <label for="{{$name}}" class="control-label text-gray {{$top_label_class or ''}}"
               style="{{$top_label_style or 'margin-top:10px'}}">{{ $top_label }}...</label>
    @endif
    @if(!isset($placeholder))
        @if(Lang::has('validation.attributes_placeholder.' . $name))
            @php $placeholder = trans('validation.attributes_placeholder.' . $name) @endphp
        @else
            @php $placeholder = '' @endphp
        @endif
    @endif
    @if(isset($addon))
        <div class="input-group {{ $group_class or '' }}">
        @endif
            <input
                    type="{{$type or 'text'}}"
                    id="{{ isset($id) ? $id : $name  }}"
                    name="{{$name}}" value="{{$value or ''}}"
                    class="form-control @if(isset($icon) and $icon) has-icon @endif {{$class or ''}}"
                    style="{{$style or ''}}"
                    placeholder="{{ $placeholder }}"
                    onkeyup="{{$on_change or ''}}"
                    onblur="{{$on_blur or ''}}"
                    onfocus="{{$on_focus or ''}}"
                    aria-valuenow="{{$value or ''}}"
                    error-value="{{ isset($error_value) ? $error_value : trans('validation.javascript_validation.' . $name)  }}"
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
{{--                    {{ dd($extra) }}--}}
                    autocomplete="off"
            >
            @if(isset($icon) and $icon)
                <i class="fa fa-{{ $icon }}"></i>
            @endif
            @if(isset($addon))
                <span class="input-group-addon f10 {{$addon_class or ''}}"
                      onclick="{{$addon_click or ''}}">{{ $addon }}</span>
        </div>
    @endif

@endif
