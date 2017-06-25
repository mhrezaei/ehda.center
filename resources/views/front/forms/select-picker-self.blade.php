@if(!isset($condition) or $condition)
    @if(isset($top_label))
        <label for="{{$name}}" class="control-label text-gray {{$top_label_class or ''}}"
               style="{{$top_label_style or 'margin-top:10px'}}">{{ $top_label }}...</label>
    @endif
    @if(isset($addon))
        <div class="input-group {{ $group_class or '' }}">
            @endif
            <select
                    type="{{$type or 'text'}}"
                    id="{{ isset($id) ? $id : $name  }}"
                    name="{{$name}}" value="{{$value or ''}}"
                    class="form-control form-selectpicker selectpicker @if(isset($icon) and $icon) has-icon @endif {{$class or ''}}"
                    style="{{$style or ''}}"
                    placeholder="{{ isset($placeholder) ? $placeholder : trans('validation.attributes_placeholder.' . $name)  }}"
                    onkeyup="{{$on_change or ''}}"
                    onblur="{{$on_blur or ''}}"
                    onfocus="{{$on_focus or ''}}"
                    aria-valuenow="{{$value or ''}}"
                    data-size= "{{$size or 5}}"
                    data-live-search="{{$search or false}}"
                    data-live-search-placeholder= "{{$search_placeholder or trans('forms.button.search')}}..."
                    data-none-selected-text = "{{ trans('forms.general.select_default') }}"
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
                    error-value="{{ isset($error_value) ? $error_value : trans('validation.javascript_validation.' . $name)  }}"
                    {{ $att or '' }}
            >
                @if(isset($blank_value) and $blank_value!='NO')
                    <option value="{{$blank_value}}"
                            @if(!isset($value) or $value==$blank_value)
                            selected
                            @endif
                    ></option>
                @endif
                @foreach($options as $option)
                    <option value="{{$option['id']}}"
                            @if(isset($value) and $value==$option['id'])
                            selected
                            @endif
                    >
                        {{$option['title']}}</option>
                @endforeach
            </select>
            @if(isset($icon) and $icon)
                <i class="fa fa-{{ $icon }}"></i>
            @endif
            @if(isset($addon))
                <span class="input-group-addon f10 {{$addon_class or ''}}"
                      onclick="{{$addon_click or ''}}">{{ $addon }}</span>
        </div>
    @endif

@endif


