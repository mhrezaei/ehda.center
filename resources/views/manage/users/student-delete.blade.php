@include('templates.modal.start' , [
	'form_url' => url('manage/students/save/delete'),
	'modal_title' => trans('ehda.students.delete'),
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
		'text' => trans('ehda.students.delete_info'),
		'shape' => "warning",
	])

	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('ehda.students.delete'),
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