@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/password'),
	'modal_title' => trans('people.user_role'),
])
<div class='modal-body'>

	{{--
	|--------------------------------------------------------------------------
	| Form Header
	|--------------------------------------------------------------------------
	|
	--}}


	@include('forms.hiddens' , ['fields' => [
		['id' , isset($model)? $model->id : '0'],
	]])


	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.name_first'),
		'value' => $model->full_name ,
		'extra' => 'disabled' ,
	])

	{{--
	|--------------------------------------------------------------------------
	| Role Browser
	|--------------------------------------------------------------------------
	|
	--}}


	@foreach($model->rolesTable() as $role)
		@include("forms.select" , [
			'name' => "role-$role->slug",
			'value' => $model->as($role->slug)->status ,
			'options' => $model->roleStatusCombo(),
			'value_field' => "0",
			'caption_field' => "1",
			'label' => trans($role->title),
		])
	@endforeach

	{{--
	|--------------------------------------------------------------------------
	| Form Footer
	|--------------------------------------------------------------------------
	|
	--}}


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