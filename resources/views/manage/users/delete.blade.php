@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/delete'),
	'modal_title' => trans('forms.button.soft_delete'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.name_first'),
		'value' => $model->full_name ,
		'extra' => 'disabled' ,
	])
	
	@include("forms.note" , [
		'condition' => $model->roles()->count() > 0,
		'text' => trans('people.form.delete_notice_when_has_role'),
		'shape' => "danger",
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.soft_delete'),
		'shape' => 'danger',
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