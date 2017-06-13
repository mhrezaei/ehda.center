<?php
if(isset($value) and is_object($value))
$value = $value->$name ;

if(!isset($disabled))
    $disabled = false ;
?>
@if(!isset($condition) or $condition)
    <div id="{{$id or ''}}" class="checkbox {{$div_class or ''}} {{ $disabled? 'text-grey' : '' }} " >
        <label title="{{ $title or '' }}" style="cursor:{{$disabled? 'no-drop' : ''}}">
            <input type="hidden" name="{{$name}}" value="0">
            {!! Form::checkbox($name , '1' , $value , [
                'class' => isset($class)? $class : '' ,
                $disabled? 'disabled' : '' => 1 ,
            ]) !!}
            {{ $label or trans("validation.attributes.$name") }}
        </label>
    </div>
@endif