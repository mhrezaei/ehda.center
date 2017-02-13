{{--
|--------------------------------------------------------------------------
| Inserts a dropdown action button
|--------------------------------------------------------------------------
| Parameters: $id AND $actions = ['fa_icon' , 'caption' , 'link or js_command' , optional permit command , optional boolian condition]
--}}

<span class="dropdown">
	<button id="action{{$id}}" class="btn btn-{{$button_class or 'default'}} btn-{{$button_size or 'xs'}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {{$button_extra or ''}}>
		{{$button_label or trans('forms.button.action')}}
	</button>
	<ul class="dropdown-menu" aria-labelledby="action{{$id}}">
		@foreach($actions as $action)
			@if($action[0]== '-')
				<li>
					<hr class="mv5">
				</li>
			@else
				<?php
					//first things first...
					$icon = $action[0] ;
					$caption = $action[1] ;
					$extra = null ;

					//target...
					$action[2] = str_replace('-id-' , $id , $action[2]);
					if(str_contains($action[2],'(')) {
						$js_command = $action[2] ;
						$target = 'javascript:void(0)' ;
					}
					elseif(str_contains($action[2] , 'modal')) {
						$target = 'javascript:void(0)' ;
						$array = explode(':',$action[2]) ;
						if(!isset($array[2])) $array[2] = 'lg' ;
						$js_command = "masterModal('". url($array[1]) ."' , '". $array[2] ."' )" ;
					}
					elseif(str_contains($action[2] , 'url')) {
						$array = explode(':',$action[2]) ;
						$target = url($array[1]) ;
						$js_command = null ;
						if(str_contains($action[2] , 'urlN'))
							$extra .= ' target="_blank" ';
					}
					else {
						$js_command = null ;
						$target = $action[2] ;
					}

					//rest...
					if(isset($action[3]))
						$permit = Auth::user()->can($action[3]) ;
					else
						$permit = true ;

					if($permit)
						if(isset($action[4]))
							$permit = $action[4]  ;

				?>
				@if($permit)
					<li>
						<a href="{{$target}}" onclick="{{$js_command}}" {{$extra}}>
							<i class="fa fa-{{$icon or 'circle'}} fa-fw"></i>
							{{ $caption }}
						</a>
					</li>
				@endif
			@endif
		@endforeach
	</ul>
</span>
