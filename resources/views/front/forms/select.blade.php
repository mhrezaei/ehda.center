<?php
if (!isset($extra))
    $extra = '';
if (!isset($name))
    $name = '';

if (isset($class) && str_contains($class, 'form-required')) {
    $required = true;
}

if (isset($value) and is_object($value))
    $value = $value->$name;

if (isset($disabled) and $disabled) {
    $required = false;
    $extra .= ' disabled ';
}
?>
@if(!isset($condition) or $condition)

    <div class="form-group {{$div_class or ''}}">

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
                {{$label or trans("validation.attributes.$name")}}
                @if(isset($required) and $required)
                    <span class="fa fa-star required-sign " title="{{trans('forms.logic.required')}}"></span>
                @endif
            </label>
        @endif

        <div class="col-sm-12">
            @include('front.forms.select_self')
            <span class="help-block {{$hint_class or ''}}">
				{{ $hint or '' }}
			</span>

        </div>
    </div>
@endif