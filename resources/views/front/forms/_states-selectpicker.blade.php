<?php
if (!isset($class)) {
    $class = '';
}

if (!isset($blank_value)) {
    $blank_value = '0';
}
?>

@include('front.forms.select-picker' , [
    'blank_value' => $blank_value,
    'options' => model('State')::combo(),
    'search' => true,
        'class' => $class,
])