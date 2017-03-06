<?php
if(!isset($name))
	$name = '' ;
if(!isset($extra))
	$extra = '' ;

if(isset($class)) {
	if(str_contains($class, 'form-required')) {
		$required = true;
	}
}
else
	$class = '' ;

if(isset($disabled) and $disabled) {
	$required = false ;
	$extra .= ' disabled ' ;
}


if(isset($value) and is_object($value))
	$value = $value->$name ;

if(!isset($in_form))
	$in_form = true ;
?>

@if(!isset($condition) or $condition)
	@if($in_form)
		<div class="form-group">
			<label
					for="{{$name}}"
					class="col-sm-2 control-label {{$label_class or ''}}"
			>
				@if(isset($label))
					{{ $label }}
				@else
					{{ Lang::has("validation.attributes.$name") ? trans("validation.attributes.$name") : $name}}
				@endif
				@if(isset($required) and $required)
					<span class="fa fa-star required-sign " title="{{trans('forms.logic.required')}}"></span>
				@endif
			</label>

			<div class="col-sm-10">
				@include('forms.input-self')
				<span class="help-block persian {{$hint_class or ''}}" style="{{$hint_style or ''}}">
					{{ $hint or '' }}
				</span>
			</div>
		</div>
	@else
		@include('forms.input-self')
	@endif
@endif