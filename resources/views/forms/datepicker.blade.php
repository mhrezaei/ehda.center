<?php
if(!isset($name))
	$name = 'date' ;

if(!isset($id))
	$id = 'txtDate'.rand(1,1000) ;

if(isset($value) and is_object($value))
	$value = $value->$name ;

if(isset($class))
	$class = 'datepicker '.$class ;
else
	$class = 'datepicker ';

if(isset($value) and $value) {
	$j_value = jdate($value)->format('Y/m/d');
	$carbon = new \Carbon\Carbon($value);
	$value = $carbon->toDateString() ;

}
else {
	$j_value = '' ;
	$value = '' ;
}
?>

@if(!isset($condition) or $condition)

	@include('forms.input' , [
		'name' => $name,
		'label' => isset($label)? $label : trans("validation.attributes.$name"),
		'value' => $j_value,
		'id' => $id,
		'type' => isset($type)? $type : '',
		'class' => $class,
		'placeholder' =>  isset($placeholder)? $placeholder : '',
		'hint' => isset($hint)? $hint : '',
		'extra' => isset($extra)? $extra : '',
	])
	@include('forms.hidden' , [
		'name' => $name ,
		'id' => $id."_extra" ,
		'value' => $value ,
		'class' => '' ,
	])
@endif