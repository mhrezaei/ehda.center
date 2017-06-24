<?php
if(!isset($name)) {
    $name = 'gender';
}
?>

@include('front.forms.buttonset', [
	'options' => \Illuminate\Support\Facades\Lang::get('forms.gender'),
])