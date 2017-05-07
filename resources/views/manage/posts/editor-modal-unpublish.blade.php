@include("templates.modal.start" , [
	'partial' => false,
	'modal_title' => trans('posts.form.unpublish'),
	'modal_id' => "modalPostUnpublishWarning",
	'modal_size' => "m",
])

	<div class="modal-body">

		@include("forms.note" , [
			'text' => trans('posts.form.unpublish_warning'),
			'shape' => "warning",
		])
		<div class="text-center m20">
			<button type="submit" name="_submit" onclick='$(".modal").modal("hide")' value="unpublish" class="btn btn-lg btn-warning w50">{{ trans('posts.form.sure_unpublish') }}</button>
		</div>
		<div class="text-center m20">
			<button class="btn btn-link" type="button"  onclick='$(".modal").modal("hide")'>{{ trans("forms.button.cancel") }}</button>
		</div>

	</div>


@include("templates.modal.end" , [
	'no_form' => true ,
])