<?php
//class...
!isset($size)? $size = 10 : '' ;
!isset($color)? $color = 'gray' : '' ;
!isset($class)? $class = '' : '' ;
!isset($default)? $default = 'relative' : '' ;
!isset($inline)? $inline = false : '' ;
$class = " f".$size." text-$color $class" ;
?>

@if(!isset($condition) or $condition)
	@if(!$inline)
		<div>
	@endif

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
			{{ $text2 or '' }}
		</a>
		@if(isset($by))
			<span class="{{$class}}">&nbsp;{{ trans('forms.general.by') }}&nbsp;{{ $by }}</span>
		@endif
	</span>

	@if(!$inline)
		</div>
	@endif

@endif
