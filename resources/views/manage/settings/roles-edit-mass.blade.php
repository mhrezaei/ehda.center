@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/role-mass'),
	'modal_title' => trans("people.all_admin_roles"),
])
<div class='modal-body'>

	@include('forms.input' , [
		'name' => '',
		'id' => 'txtCount' ,
		'label' => trans('validation.attributes.items'),
		'value' => pd(count(model('role')::adminRoles())).' '.trans("forms.general.numbers"). ' '.trans("people.user_role"),
		'extra' => 'disabled' ,
	])

	@include("manage.frame.widgets.input-textarea" , [
		'label' => trans('people.modules'),
		'name' => "modules",
		'value' => $model->modules_for_input,
		'class' =>	'ltr form-autoSize',
		'rows' => "3",
	])

	@include("manage.frame.widgets.input-textarea" , [
		'name' => "status_rule",
		'value' => $model->spreadMeta()->status_rule_for_input ,
		'class' =>	'ltr form-autoSize',
		'rows' => "3",
	]     )


	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('forms.button.save'),
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

</div>
@include('templates.modal.end')