<?php

if(!isset($target)) {
	$target = 'javascript:void(0)' ;
	$on_click = '' ;
}
elseif(str_contains($target,'(')) {
	$on_click = $target ;
	$target = 'javascript:void(0)' ;
}
elseif(str_contains($target , 'modal')) {
	$array = explode(':',$target) ;
	if(!isset($array[2])) $array[2] = 'lg' ;
	$on_click = "masterModal('". url($array[1]) ."' , '". $array[2] ."' )" ;
	$target = 'javascript:void(0)' ;
}
else {
	$target = url($target) ;
}

?>

@if(!isset($condition) or $condition)
	<a href="{{$target}}" class="btn btn-{{$type or 'default'}} btn-sm" title="{{$caption or ''}}" onclick="{{$on_click or ''}}">
		<i class="fa fa-{{$icon or 'dot-circle-o'}}"></i>
		{{ $caption }}
	</a>
@endif
