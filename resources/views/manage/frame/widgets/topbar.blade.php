<li id="{{$id or ''}}" class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:{{ $color or '' }}">
		@if(isset($counter) and $counter>0)
			<span class="counter">
				@pd($counter)
			</span>
		@endif
		<i class="fa fa-{{$icon or 'navicon'}} fa-fw"></i>
		<span class="topbar {{$text_class or ''}}">
			@if(isset($text))
				<span class="mh5">{{$text}}</span>
			@endif
		</span>
		<i class="fa fa-caret-down"></i>
	</a>
	<ul class="dropdown-menu">

		@foreach($items as $key => $item)
			@if($key === 'total')
			@else
				@if($item[0] == '-' )
					<li class="divider"></li>
				@elseif(isset($item[3]) and !$item[3])
				@else
					@include('manage.frame.use.navbar-dropdown-link' , [
						'target' => url($item[0]),
						'caption'=> $item[1],
						'icon' => $item[2]
					])
				@endif
			@endif
		@endforeach
	</ul>
</li>

