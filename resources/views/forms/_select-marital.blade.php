<?php
if (!isset($name)) {
    $name = 'marital';
}

if (!isset($class)) {
    $class = '';
}

if (!isset($blank_value)) {
    $blank_value = '0';
}

$acceptableValues = [1, 2]; // single and married are acceptable

foreach ($acceptableValues as $acceptableValue) {
    $options[] = [
        'id' => $acceptableValue,
        'title' => trans('people.marital.' . $acceptableValue)
    ];
}
?>

@include('front.forms.select-picker' , [
    'blank_value' => $blank_value,
    'class' => $class,
    'options' => $options,
])

<div class="col-xs-12">
    <span class="help-block persian {{$hint_class or ''}}" style="{{$hint_style or ''}}">
        {{ $hint or '' }}
    </span>
</div>
