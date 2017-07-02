<span class="dropdown">
	<span class="fa fa-caret-down clickable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>

	<div class="dropdown-menu">
		@foreach($locales as $locale)
			@if( in_array( $request_role->slug , model('role')::adminRoles()) and user()->as_any()->can("$module.$permit.$locale"))
				<div class="clickable p5 -{{$module}}-{{$permit}}-locale -permit-link" locale="{{$locale}}" checker="{{"$module.$permit.$locale"}}" for="locale" value="" onclick="permitClick($(this))">
					<span class="fa -locale-handle "></span>
					<span class="">{{ trans("forms.lang.$locale") }}</span>
				</div>
			@endif
		@endforeach
	</div>

</span>
