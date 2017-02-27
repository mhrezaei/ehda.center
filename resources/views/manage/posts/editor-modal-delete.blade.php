@include("templates.modal.start" , [
	'partial' => false,
	'modal_title' => trans('forms.button.soft_delete'),
	'no_validation' => "1",
	'modal_id' => "modalPostDeleteWarning",
	'modal_size' => "m",
])

	<div class="modal-body">
		{{--
		|--------------------------------------------------------------------------
		| In 'create' mode where nothing is saved yet!
		|--------------------------------------------------------------------------
		| 
		--}}

		@if(!$model->id)
			@include("forms.note" , [
				'text' => trans('posts.form.delete_alert_for_unsaved_post'),
				'shape' => "danger",
			])
			<div class="text-center m20">
				<a href="{{ url("manage/posts/$model->type") }}" class="btn btn-lg btn-danger w50">{{ trans('forms.button.sure_delete') }}</a>
			</div>
			<div class="text-center m20">
				<button class="btn btn-link" onclick='$(".modal").modal("hide")'>{{ trans("forms.button.cancel") }}</button>
			</div>
		@endif


		{{--
		|--------------------------------------------------------------------------
		| If model is published
		|--------------------------------------------------------------------------
		|
		--}}

		@if($model->isPublished())
		@endif


		{{--
		|--------------------------------------------------------------------------
		| If model is not published, but
		|--------------------------------------------------------------------------
		|
		--}}

		@endif
	</div>


@include("templates.modal.end")