<?php
//class...
!isset($size)? $size = 8 : '' ;
!isset($color)? $color = 'grey' : '' ;
!isset($class)? $class = '' : '' ;
!isset($default)? $default = 'relative' : '' ;
$class = " f".$size." text-$color $class" ;
?>

@if(!isset($condition) or $condition)
	<span class="">
		<a id="{{ $id = "spnDate".rand(10000,99999) }}" href="javascript:void(0)"  class="{{$class}}" onclick="$('#{{$id}} text').toggle()">
			<i class="fa fa-{{$icon or 'clock-o'}} mhl5"></i>
			{{$text or '' }}
			<text class="{{ $default=='fixed'?'noDisplay':'' }} {{$class or ''}}">
				@pd(jDate::forge($date)->ago())
			</text>
			<text class="{{ $default=='relative'?'noDisplay':'' }} {{$class or ''}}">
				@pd(jDate::forge($date)->format('j F Y [H:i]'))
			</text>
		</a>
	</span>

@endif
