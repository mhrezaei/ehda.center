@include('templates.modal.start' , [
	'form_url' => url('manage/settings/save/pack'),
	'modal_title' => $model->id? trans('posts.packs.edit') : trans('posts.packs.add'),
	'no_validation' => true ,
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id],
		['type' , $model->type],
	]])

	@foreach($model->posttype->locales_array as $locale)
		@include('forms.input' , [
			'name' =>	$locale=='fa'? 'title' : "_title_in_$locale",
			'label' => $locale=='fa'? trans('validation.attributes.title') : trans("forms.lang.$locale"),
			'value' =>	$model->titleIn($locale),
			'class' =>  in_array($locale , ['fa' , 'ar']) ? 'form-required' : 'form-required ltr' ,
			'hint' =>	$locale=='fa'? trans('validation.hint.unique').' | '.trans('validation.hint.persian-only') : '',
		])
	@endforeach

	@include("forms.select" , [
		'name' => "unit_id",
		'value' => $model->unit_id,
		'options' => $model->unitsCombo(),
	])


	@include("manage.frame.widgets.input-photo" , [
		'name' => "image",
		'value' => $model->image,
	])


	@include('forms.group-start')

		@include('forms.button' , [
			'id' => 'btnSave' ,
			'condition' => !$model->trashed() ,
			'label' => trans('forms.button.save'),
			'shape' => 'primary',
			'type' => 'submit' ,
			'value' => 'save' ,
			'class' => '-delHandle'
		])

		@include('forms.button' , [
			'condition' => $model->id and !$model->trashed() ,
			'id' => 'btnDeleteWarning' ,
			'label' => trans('posts.packs.deactivate'),
			'shape' => 'warning',
			'type' => "submit",
			'value' => "delete" ,
			'name' => "delete" ,
			'class' => '-delHandle' ,
		])
		@include('forms.button' , [
			'condition' => $model->id and $model->trashed() ,
			'id' => 'btnDelete' ,
			'label' => trans('manage.permissions.activate'),
			'shape' => 'success',
			'value' => 'undelete' ,
			'type' => 'submit' ,
		])



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