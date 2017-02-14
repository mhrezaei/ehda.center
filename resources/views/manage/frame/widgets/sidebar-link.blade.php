@if(!isset($permission) or user()->as('admin')->can($permission))
	<li {{ (Request::is('*'.$link) ? 'class="active"' : '') }}>

		{{--
		|--------------------------------------------------------------------------
		| Main Manu
		|--------------------------------------------------------------------------
		| 
		--}}

		@if(isset($disabled) and $disabled)
			<a href="javascript:void(0)" class="null-content" style="cursor: default;z-index: -1">
				<i class="fa fa-{{ $icon or 'dot-circle-o' }} fa-fw" style="width: 20px"></i>
				&nbsp;{{ $caption }}&nbsp;
			</a>
		@else
			<a href="{{ url ('manage/'.$link) }}">
				<i class="fa fa-{{ $icon or 'dot-circle-o' }} fa-fw"></i>
				&nbsp;{{ $caption }}&nbsp;
				@if(isset($sub_menus))
					<span class="fa arrow"></span>
				@endif
			</a>
		@endif

		{{--
		|--------------------------------------------------------------------------
		| Sub Menus
		|--------------------------------------------------------------------------
		|
		--}}

		@if(isset($sub_menus))
			<ul class="nav nav-second-level">
				@foreach($sub_menus as $sub_menu)  {{--  [0:target 1:caption 2:icon 3:permission  --}}
					@if(!isset($sub_menu[3]) or Auth::user()->can($sub_menu[3]))
						<li {{ (Request::is('*'.$sub_menu[0]) ? 'class="active"' : '') }}>
							<a href="{{ url ("manage/".$sub_menu[0]) }}">
								<i class="fa fa-{{ $sub_menu[2] or 'dot-circle-o' }} fa-fw" style="width: 20px"></i>
								&nbsp;{{ $sub_menu[1] }}&nbsp;
							</a>
						</li>
				@endif
				@endforeach
			</ul>
		@endif

	</li>
@endif