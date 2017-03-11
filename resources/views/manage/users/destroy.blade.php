@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/destroy'),
	'modal_title' => trans('forms.button.hard_delete'),
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
		'text' => trans('people.form.hard_delete_notice'),
		'shape' => "danger",
	])
	
	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.sure_hard_delete'),
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