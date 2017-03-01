{{--
| moderate_note (#txtModerateNote) and submit button (#btnReject) is included in editor-hiddens
--}}


@include("templates.modal.start" , [
	'partial' => false,
	'modal_title' => trans('posts.form.refer_back'),
	'no_validation' => "1",
	'modal_id' => "modalPostReject",
	'modal_size' => "m",
])

	<div class="modal-body">
		@include("forms.textarea" , [
			'name' => "",
			'label' => trans('validation.attributes.moderate_note'),
			'id' => "txtModerateNote2",
			'class' => "form_required",
		])

		@include('forms.group-start')

			@include('forms.button' , [
				'label' => trans('posts.form.refer_back'),
				'shape' => 'warning',
				'link' => 'postsReject()',
			])

			@include('forms.button' , [
				'label' => trans('forms.button.cancel'),
				'shape' => 'link',
				'link' => '$(".modal").modal("hide")',
			])

		@include('forms.group-end' , [
//			'no_form' => "1",
		])

	</div>


@include("templates.modal.end")