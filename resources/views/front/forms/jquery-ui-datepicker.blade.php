<?php
if (!isset($name))
    $name = '';
if (!isset($extra))
    $extra = '';

if (isset($class)) {
    if (str_contains($class, 'form-required')) {
        $required = true;
    }
} else {
    $class = '';
}

$class .= ' form-datepicker';

if (isset($disabled) and $disabled) {
    $required = false;
    $extra .= ' disabled ';
}


if (isset($value) and is_object($value))
    $value = $value->$name;

if (!isset($in_form))
    $in_form = true;


if (!isset($options)) {
    $options = [];
}
if (!isset($options['language'])) {
    $options['language'] = getLocale();
}
if ($options['language'] != 'fa') {
    $options['regional'] = '';
}

foreach ($options as $optionName => $optionValue) {
    $dataAttributes['datepicker-' . kebab_case($optionName)] = $optionValue;
}

?>
@if(!isset($condition) or $condition)
    @if($in_form)
        <div class="form-group {{ (isset($container['class']) ? $container['class'] : '') }}"
        {{ isset($container['id']) ? "id=$container[id]" : '' }}
        @if(isset($container['other']))
            @foreach($container['other'] as $attrName => $attrValue)
                {{ $attrName }}="{{ $attrValue }}"
            @endforeach
        @endif
        >
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
            @include('front.forms.input-self')
            <span class="help-block persian {{$hint_class or ''}}" style="{{$hint_style or ''}}">
					{{ $hint or '' }}
				</span>

            @if ($errors->has($name))
                <span class="help-block error">
                                    <strong>{{ $errors->first($name) }}</strong>
                                </span>
            @endif

        </div>
        </div>
    @else
        @include('front.forms.input-self')
    @endif
@endif