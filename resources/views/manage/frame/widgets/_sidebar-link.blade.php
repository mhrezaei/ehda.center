@if(Auth::user()->can($module))

	<?php
		if(str_contains($module,'posts-')) {
			$slug = str_replace('posts-','',$module) ;
			$caption = \App\Models\Post_cat::getName($slug);
		}
		else {
			$caption = trans('manage.modules.'.$module) ;
		}
	?>

	<li {{ (Request::is('*'.$module) ? 'class="active"' : '') }}>
		<a href="{{ url ('manage/'.$module) }}">
			<i class="fa fa-{{ $icon or 'dot-circle-o' }} fa-fw"></i>
			&nbsp;{{ $caption }}&nbsp;
			@if(isset($sub_menus))
				<span class="fa arrow">
			@endif
		</a>

		@if(isset($sub_menus))
			<ul class="nav nav-second-level">
				@foreach($sub_menus as $sub_menu)
					<?php
						if(is_array($sub_menu)) {
							$target = "manage/$module/".$sub_menu[0] ;
							$caption = $sub_menu[1] ;
							$permit = $sub_menu[2] ;
						}
						elseif(substr($sub_menu , 0 , 1)=='.') {
							$permit = ltrim($sub_menu,'.') ;
							$target = "manage/$module/".$permit ;
						}
						else {
							$permit = $sub_menu ;
							$target = "manage/$sub_menu";
						}

						if(str_contains($sub_menu,'posts-')) {
							$slug = str_replace('posts-','',$sub_menu) ;
							$caption = \App\Models\Post_cat::getName($slug);
						}
						else {
							$caption = trans('manage.permits.'.$permit) ;
						}

					?>
					@if(Auth::user()->can($module.$permit))
						<li {{ (Request::is('*'.$target) ? 'class="active"' : '') }}>
							<a href="{{ url ($target) }}">{{ $caption }}</a>
						</li>
					@endif
				@endforeach
			</ul>
		@endif

	</li>
@endif
