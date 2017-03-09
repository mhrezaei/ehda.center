@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/password'),
	'modal_title' => trans('people.commands.change_password'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , isset($model)? $model->id : '0'],
	]])


	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.name_first'),
		'value' => $model->full_name ,
		'extra' => 'disabled' ,
	])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.mobile'),
		'value' => $model->mobile ,
		'extra' => 'disabled' ,
	])

	@include('forms.input' , [
		'name' => 'password',
		'value' => rand(10000000 , 99999999),
		'class' => 'form-required ltr form-default' ,
		'hint' => trans('people.form.password_hint')
	])

	@include('forms.group-start')

	@include('forms.check' , [
		'name' => 'sms_notify',
		'label' => trans('people.form.notify-with-sms'),
		'value' => 1,
		])

	@include('forms.group-end')


	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.save'),
		'shape' => 'success',
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