<span class="dropdown">
	<span class="fa fa-caret-down clickable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>

	<div class="dropdown-menu">
		@foreach($locales as $locale)
			<div class="clickable p5">
				<span class="fa fa-square-o"></span>
				<span class="">{{ trans("forms.lang.$locale") }}</span>
			</div>
		@endforeach
	</div>

</span>
