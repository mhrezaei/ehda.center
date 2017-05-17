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

	@include('forms.input' , [
	    'name' =>	'title',
	    'value' =>	$model->title,
	    'class' => 'form-required  form-default' ,
	    'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
	])

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
			'label' => trans('forms.button.save'),
			'shape' => 'success',
			'type' => 'submit' ,
			'value' => 'save' ,
			'class' => '-delHandle'
		])

			@include('forms.button' , [
				'condition' => $model->id and !$model->trashed() ,
				'id' => 'btnDeleteWarning' ,
				'label' => trans('posts.packs.deactivate'),
				'shape' => 'warning',
//				'link' => '$(".-delHandle").toggle()' ,
				'type' => "submit",
				'name' => "delete" ,
				'class' => '-delHandle' ,
			])
			@include('forms.button' , [
				'condition' => $model->id and $model->trashed() ,
				'id' => 'btnDelete' ,
				'label' => trans('forms.button.forms.button.sure_delete'),
				'shape' => 'danger',
				'value' => 'undelete' ,
				'type' => 'submit' ,
//				'class' => 'noDisplay -delHandle' ,
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