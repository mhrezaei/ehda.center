@include('templates.modal.start' , [
	'form_url' => url('manage/categories/save/folder'),
	'modal_title' => $model->id? trans('forms.button.edit') : trans('posts.categories.new_folder'),
	'no_validation' => true ,
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id],
		['locale' , $model->locale],
		['posttype_id' , $model->posttype_id],
	]])

	@include('forms.input' , [
	    'name' =>	'title',
	    'value' =>	$model->title,
	    'class' => 'form-required  form-default' ,
	    'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
	])

	@include('forms.input' , [
		'name' =>	'slug',
		'class' =>	'form-required ltr' ,
		'value' =>	$model->slug ,
		'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.english-only') . ' | '.trans('validation.hint.no_underline'),
	])

	@include("manage.frame.widgets.input-photo" , [
		'name' => "image",
		'condition' => $type->has('cat_image'),
		'value' => $model->image,
	])


	@if($model->id and $posts = $model->categories()->count())
		@include('forms.note' , [
			'shape' => 'warning' ,
			'text' => trans('posts.categories.folder_delete_notice' , ['count' => $posts]) ,
			'class' => '-delHandle noDisplay'
		])
	@endif


	@include('forms.note' , [
		'shape' => 'danger' ,
		'text' => trans('people.form.hard_delete_notice') ,
		'class' => '-delHandle noDisplay'
	])

	@include('forms.group-start')

		@include('forms.button' , [
			'id' => 'btnSave' ,
			'label' => trans('forms.button.save'),
			'shape' => 'success',
			'type' => 'submit' ,
			'value' => 'save' ,
			'class' => '-delHandle'
		])

		@if($model->id)
			@include('forms.button' , [
				'id' => 'btnDeleteWarning' ,
				'label' => trans('forms.button.delete'),
				'shape' => 'warning',
				'link' => '$(".-delHandle").toggle()' ,
				'type' => "button",
				'class' => '-delHandle' ,
			])
			@include('forms.button' , [
				'id' => 'btnDelete' ,
				'label' => trans('forms.button.sure_hard_delete'),
				'shape' => 'danger',
				'value' => 'delete' ,
				'type' => 'submit' ,
				'class' => 'noDisplay -delHandle' ,
			])

		@endif


		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")',
		])

	@include('forms.group-end')

	@include('forms.feed')

	@include('forms.closer')

</div>
@include('templates.modal.end')