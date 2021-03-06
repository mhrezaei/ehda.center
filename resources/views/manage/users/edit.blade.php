@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/'),
	'modal_title' => $model->id? trans('forms.button.edit')  : trans('people.commands.create_new_user' , ['role_title' => trans('people.user'),]),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
		['_role_to_be_attached' , $model->role_to_be_attached]
	]])

	@include('forms.input' , [
		'name' => 'name_first',
		'value' => $model->name_first ,
		'class' => 'form-required form-default' ,
	])

	@include('forms.input' , [
	    'name' => 'name_last',
	    'class' => 'form-required',
	    'value' => $model->name_last
	])

	@include('forms.input' , [
	    'name' => 'code_melli',
	    'class' => 'form-required',
	    'value' => $model->code_melli
	])

	@include('forms.input' , [
		'name' => 'email',
		'class' => 'form-required ltr',
		 'value' => $model->email ,
	])

	@include('forms.input' , [
		'name' => 'mobile',
		'class' => 'form-required ltr',
		'value' => $model->mobile ,
	])

	@include('forms.input' , [
		'name' => '' ,
		'condition' => !$model->exists,
		'label' => trans('validation.attributes.password'),
		'extra' => 'disabled' ,
		'class' => 'f10' ,
		'value' => trans('forms.feed.password_set_to_mobile'),
	])

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