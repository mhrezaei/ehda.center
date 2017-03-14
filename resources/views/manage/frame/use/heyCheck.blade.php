<div id="divHeyCheck">
	<div class="m20">

		@include("forms.note" , [
			'text' => trans("manage.session_expired_notice"),
			'shape' => "danger",
		])

		<button type="button" class="btn btn-lg btn-primary" onclick="$('#iHeyCheck').slideDown('fast')">{{ trans('forms.button.login') }}</button>
		<iframe id="iHeyCheck" src="{{ url('/login') }}"></iframe>

	</div>


@include("templates.modal.end")
</div>


