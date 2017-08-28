@include('templates.modal.start' , [
	'form_url' => url('manage/students/save/'),
	'modal_title' => trans("people.commands.create_new_user" , ['role_title' => trans("ehda.students.single") ,	]),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , 0 ],
	]])

	@include('forms.input' , [
		'name' => 'code_melli',
		'class' => "form-required form-default" ,
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.save'),
		'shape' => 'success',
		'type' => 'submit' ,
	])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')