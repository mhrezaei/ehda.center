<?php
if (!isset($name)) {
    $name = 'birth_date';
}

$options['maxDate'] = 0;
$options['changeYear'] = true;
$options['yearRange'] = '-100:+0';
$options['changeMonth'] = true;

?>
@include('front.forms.jquery-ui-datepicker')