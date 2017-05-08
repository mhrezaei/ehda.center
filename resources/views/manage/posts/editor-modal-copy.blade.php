{{--
| Appears once, when a change is detected on a published page
--}}


@include("templates.modal.start" , [
	'partial' => false,
	'modal_title' => trans('posts.form.refer_back'),
	'no_validation' => "1",
	'modal_id' => "modalSuggestCopy",
	'modal_size' => "m",
])

	<div class="modal-body">

		@if($model->canPublish())
			@include("forms.note" , [
				'text' => trans('posts.form.copy_suggestion_when_can_publish'),
				'shape' => "info",
			])
			<div class="text-center m20">
				<button type="submit" name="_submit" onclick='$(".modal").modal("hide")' value="save" class="btn btn-lg btn-primary w50">{{ trans('posts.form.save_draft') }}</button>
			</div>
			<div class="text-center m20">
				<button class="btn btn-link" type="button"  onclick='$(".modal").modal("hide")'>{{ trans("forms.feed.not_now") }}</button>
			</div>
			<div class="text-center m20 text-info">
				{{ trans('posts.form.copy_suggestion_deny') }}
			</div>
		@else
			@include("forms.note" , [
				'text' => trans('posts.form.copy_suggestion_when_cannot_publish'),
				'shape' => "danger",
			])
			<div class="text-center m20">
				<button type="submit" name="_submit" onclick='$(".modal").modal("hide")' value="save" class="btn btn-lg btn-primary w50">{{ trans('posts.form.save_draft') }}</button>
			</div>
			<div class="text-center m20">
				<button class="btn btn-link" type="button"  onclick='$(".modal").modal("hide")'>{{ trans("forms.feed.not_now") }}</button>
			</div>
			<div class="text-center m20 text-info">
				{{ trans('posts.form.copy_suggestion_deny') }}
			</div>
		@endif



	</div>


@include("templates.modal.end")