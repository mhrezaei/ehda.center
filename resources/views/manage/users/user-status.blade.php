@include('templates.modal.start' , [
	'fake' => $role = model('role')::findBySlug($option) ,
	'form_url' => url('manage/users/save/status'),
	'modal_title' => trans('forms.button.change_status'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , isset($model)? $model->id : '0'],
		['role_id' , $role->hashid]
	]])


	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.name_first'),
		'value' => $model->full_name ,
		'extra' => 'disabled' ,
	])

	@include("forms.select" , [
		'name' => "new_status" ,
		'value' => $model->as($role->slug)->status() ,
		'options' => $role->statusCombo() ,
		'class' => "form-required" ,
		'caption_field' => "1" ,
		'value_field' => "0" ,
	]     )

	{{--@include('forms.group-start')--}}

	{{--@include('forms.check' , [--}}
		{{--'name' => 'sms_notify',--}}
		{{--'label' => trans('people.form.notify-with-sms'),--}}
		{{--'value' => 1,--}}
		{{--])--}}

	{{--@include('forms.group-end')--}}


	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.change_status'),
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