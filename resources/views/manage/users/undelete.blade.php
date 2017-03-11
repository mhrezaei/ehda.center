@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/undelete'),
	'modal_title' => trans('forms.button.undelete'),
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
	
	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.undelete'),
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