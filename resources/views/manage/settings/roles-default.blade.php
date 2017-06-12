@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/role-default'),
	'modal_title' => trans('people.choose_as_default_role'),
	'no_validation' => true ,
])
<div class='modal-body'>

	@include('forms.hidden' , [
		'name' => 'id' ,
		'value' => $model->spreadMeta()->id,
	])

	@include("forms.input" , [
		'name' => "",
		'value' => $model->title ,
		'label' => trans('validation.attributes.title') ,
		'disabled' => true ,
	]     )

	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('people.choose_as_default_role') ,
			'shape' => 'primary',
			'type' => 'submit' ,
		])

		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")',
		])

	@include('forms.group-end')

	@include('forms.feed')

	@include('forms.closer')

</div>
@include('templates.modal.end')
