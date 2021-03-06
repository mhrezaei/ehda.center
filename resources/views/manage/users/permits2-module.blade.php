@if(in_array( $request_role->slug , model('role')::adminRoles()) and user()->as_any()->can($module))
	<div id="divModule-{{$module}}" class="mv10">

		<div class="module-title -permit-link -module f16 clickable" module="{{$module}}" for="module" value="" onclick="permitClick($(this))">
			<span id="spnModuleHandle-{{$module}}" class=" fa fa-circle-o mv10 mh10 f18 -module-handle"></span>
			{{ $title }}
		</div>

		@if(isset($locales) and is_array($locales) and count($locales)>1)
			{{ '' , $has_locales = 1 }}
		@else
			{{ '' , $has_locales = 0 }}
		@endif


		<div class="mv5 p10 mh30 row">
		@foreach($permits as $permit)
				@if( in_array( $request_role->slug , model('role')::adminRoles()) and user()->as_any()->can("$module.$permit"))
					<div class="col-md-3 mv5 clickable">
						<span class="-permit-link -{{$module}}-permit" module="{{$module}}" permit="{{$permit}}" hasLocale="{{$has_locales}}" checker="{{"$module.$permit"}}" for="permit" value="" onclick="permitClick($(this))">
							<span class="f15 fa mh5 fa-circle-o -permit-handle"></span>
							<span class="-permit-label f13">{{ Lang::has("manage.permissions.$permit") ? trans("manage.permissions.$permit") : $permit }}</span>
						</span>

						@if($has_locales)
							@include("manage.users.permits2-locale")
						@endif
					</div>
				@endif

			@endforeach
		</div>
	</div>
@endif