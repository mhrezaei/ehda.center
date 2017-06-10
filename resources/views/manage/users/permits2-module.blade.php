<div class="mv10">

	<div class="module-title {{$module}} f14 clickable">
		<span id="spnModuleHandle-{{$module}}" class=" fa fa-square-o mv10 mh10 f16"></span>
		{{ $title }}
	</div>


	<div class="mv5 p10 mh30 row">
		@foreach($permits as $permit)
			<div class="col-md-2 mv5 clickable -permit" permit="{{$module}}.{{$permit}}">
				<span class="fa mh5 fa-square-o -permit-handle"></span>
				<span class="-permit-label">{{ trans("manage.permissions.$permit") }}</span>

				@if(isset($locales) and is_array($locales) and count($locales)>1)
					@include("manage.users.permits2-locale")
				@endif
			</div>

		@endforeach
	</div>
</div>