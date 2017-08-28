<?php
if (!isset($extra))
    $extra = '';
if (!isset($in_form))
    $in_form = true;

if (isset($class) && str_contains($class, 'form-required')) {
    $required = true;
}

if (isset($value) and is_object($value))
    $value = $value->$name;

if (isset($disabled) and $disabled) {
    $required = false;
    $extra .= ' disabled ';
}

if (!isset($placeholder)) {
    if (Lang::has("validation.attributes_placeholder.$name")) {
        $placeholder = trans("validation.attributes_placeholder.$name");
    } else {
        $placeholder = '';
    }
}

?>
@if(!isset($condition) or $condition)

    @if($in_form)
        <div class="form-group">
            @if(!isset($label))
                {{ null, $label = Lang::has("validation.attributes.$name") ? trans("validation.attributes.$name") : $name }}
            @elseif($label)
                {{ null, $label = is_string($label) ? $label : trans("validation.attributes.$name") }}
            @endif
            @if($label)
                <label
                        for="{{$name}}"
                        class="col-sm-12 control-label {{$label_class or ''}}"
                >
                    {{ $label }}
                    @if(isset($required) and $required)
                        @include('front.forms.require-sign')
                    @endif
                </label>

            @endif
            <div class="col-sm-12">
                @endif
                @if(isset($top_label))
                    <label for="{{$name}}" class="control-label mv10 text-gray">{{ $top_label }}...</label>
                @endif
                <textarea
                        id="{{$id or ''}}"
                        name="{{$name}}" value="{{$value or ''}}"
                        class="form-control {{$class or ''}}"
                        placeholder="{{$placeholder}}"
                        rows="{{$rows or 5}}"
                        error-value="{{ isset($error_value) ? $error_value : trans('validation.javascript_validation.' . $name)  }}"
                        {{$extra or ''}}
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
                >{{$value or ''}}</textarea>
                @if($in_form)
                    <span class="help-block">
                {{ $hint or '' }}
            </span>
            </div>
        </div>
    @endif
@endif