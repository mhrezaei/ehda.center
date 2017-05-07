<?php
if(!isset($extra))
	$extra = '' ;
if(!isset($name))
	$name = '' ;

if(isset($class) && str_contains($class, 'form-required')) {
	$required = true;
}

if(isset($value) and is_object($value))
	$value = $value->$name ;

if(isset($disabled) and $disabled) {
	$required = false ;
	$extra .= ' disabled ' ;
}
?>
@if(!isset($condition) or $condition)

	<div class="form-group {{$div_class or ''}}">
		<label
				for="{{$name}}"
				class="col-sm-2 control-label {{$label_class or ''}}"
		>
			{{$label or trans("validation.attributes.$name")}}
			@if(isset($required) and $required)
				<span class="fa fa-star required-sign " title="{{trans('forms.logic.required')}}"></span>
			@endif
		</label>

		<div class="col-sm-10">
			@include('forms.select_self')
			<span class="help-block {{$hint_class or ''}}">
				{{ $hint or '' }}
			</span>

		</div>
	</div>
@endif