@include('templates.modal.start' , [
	'fake' => $states = model('state')::combo() ,
	'partial' => true ,
	'form_url' => url('manage/volunteers/save/changes'),
	'modal_title' => trans('people.commands.changes_review'),
	'no_validation' => 1
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->spreadMeta()->id ],
	]])

	@include('manage.account.profile-form-inside' , ['show_unchanged' => false])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('people.commands.changes_confirm'),
		'shape' => 'success',
		'type' => 'submit' ,
		'value' => 'save' ,
	])

	@include('forms.button' , [
		'label' => trans('forms.button.cancel'),
		'shape' => 'link',
		'link' => '$(".modal").modal("hide")',
	])

	@include('forms.group-end')

	@include('forms.sep')

	@include('forms.input' , [
		'name' => 'reject_reason',
		'value' => null ,
		'class' => 'form-required' ,
	])

	@include('forms.group-start')


	@include('forms.button' , [
		'label' => trans('people.commands.changes_reject'),
		'shape' => 'danger',
		'type' => 'submit' ,
		'value' => 'reject' ,
	])

	@include('forms.group-end')

	@include('forms.feed')

</div>

@include('templates.modal.end')