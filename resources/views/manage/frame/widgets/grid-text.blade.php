<?php
	//class...
	!isset($size)? $size = 12 : '' ;
	!isset($color)? $color = '' : '' ;
	!isset($class)? $class = '' : '' ;
	$class = " f".$size." text-$color $class" ;

	//target...
	if(isset($link) and $link) {
		$link = str_replace('-id-' , $model->id , $link);
		$extra = '' ;
		if(str_contains($link,'(')) {
			$js_command = $link ;
			$target = 'javascript:void(0)' ;
		}
		elseif(str_contains($link , 'modal')) {
			$target = 'javascript:void(0)' ;
			$array = explode(':',$link) ;
			if(!isset($array[2])) $array[2] = 'lg' ;
			$js_command = "masterModal('". url($array[1]) ."' , '". $array[2] ."' )" ;
		}
		elseif(str_contains($link , 'url')) {
			$array = explode(':',$link) ;
			$target = url($array[1]) ;
			$js_command = null ;
			if(str_contains($link , 'urlN'))
				$extra .= ' target="_blank" ';
		}
		elseif($link = '') {
			$js_command = null ;
			$target = null ;
		}
		else {
			$js_command = null ;
			$target = $link ;
		}
	}


?>
@if(!isset($condition) or $condition)
	<div class="" style="margin-bottom: 5px">
		@if(isset($link) and $link)
			<a href="{{$target}}" onclick="{{$js_command}}" class="{{$class}}" {{$extra}}>
				@if(isset($icon))
					<i class="fa fa-{{$icon}} mhl5"></i>
				@endif
				@pd($text)
			</a>
		@else
			<span class="{{$class}}">
				@if(isset($icon))
					<i class="fa fa-{{$icon}} mhl5"></i>
				@endif
				@pd($text)
			</span>
		@endif
	</div>

@endif
