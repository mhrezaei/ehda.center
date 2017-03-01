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

		@if($model->exists)
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
		| If it's a copied version
		|--------------------------------------------------------------------------
		|
		--}}

		@if($model->isCopy())
			@include("forms.note" , [
				'text' => trans('posts.form.delete_alert_for_copies'),
				'shape' => "warning",
			])
			<div class="text-center m20">
				<button type="submit" name="_submit" value="delete" class="btn btn-lg btn-warning w50">{{ trans('posts.form.delete_this_copy') }}</button>
			</div>
			<div class="text-center m20">
				<button type="submit" name="_submit" value="delete_original" class="btn btn-lg btn-danger w50">{{ trans('posts.form.delete_original_post') }}</button>
			</div>
			<div class="text-center m20">
				<button class="btn btn-link" onclick='$(".modal").modal("hide")'>{{ trans("forms.button.cancel") }}</button>
			</div>
		@endif


		{{--
		|--------------------------------------------------------------------------
		| If model is not published, but
		|--------------------------------------------------------------------------
		|
		--}}

		@if(!$model->exists and !$model->isCopy())
{{--			@include("forms.note" , [--}}
				{{--'text' => trans('delete'),--}}
				{{--'shape' => "warning",--}}
			{{--])--}}
			<div class="text-center m20">
				<button type="submit" name="_submit" value="delete" class="btn btn-lg btn-danger w50">{{ trans('forms.button.sure_delete') }}</button>
			</div>
			<div class="text-center m20">
				<button class="btn btn-link" onclick='$(".modal").modal("hide")'>{{ trans("forms.button.cancel") }}</button>
			</div>
		@endif
	</div>


@include("templates.modal.end")