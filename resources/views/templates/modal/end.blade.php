@if(0)
	<div>
		<div>
			<div>
				@endif

				@if(!isset($no_form) or !$no_form)
					@include('forms.closer')
				@endif
			</div>
		</div>
	</div>
