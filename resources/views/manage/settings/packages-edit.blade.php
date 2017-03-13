@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/package'),
	'modal_title' => $model->id? trans('forms.button.edit') : trans('forms.button.add'),
])
	<div class='modal-body'>

		@include("forms.hiddens" , [ "fields" => [
			['id' , $model->id],
			['deleted_at' , $model->deleted_at],
		]])

		@include('forms.input' , [
			'name' =>	'title',
			'value' =>	$model->title,
			'class' => 'form-required form-default' ,
			'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
		])

		@include('forms.input' , [
			'name' =>	'slug',
			'class' =>	'form-required ltr',
			'value' =>	$model->slug ,
			'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.english-only'),
		])

		@include('forms.select' , [
			'name' => 'is_continuous' ,
			'label' => trans('settings.continuity'),
			'options' => [
				'0' => ['1' , trans('settings.continuous')],
				'1' => ['0' , trans('settings.discontinuous')],
			] ,
			'caption_field' => '1' ,
			'value_field' => '0' ,
			'value' => $model->is_continuous ,
		])

		@include('forms.group-start')

		@include('forms.button' , [
			'id' => 'btnSave' ,
			'label' => trans('settings.save_as_active'),
			'shape' => 'success',
			'type' => 'submit' ,
			'value' => 'active' ,
		])
		@include('forms.button' , [
			'id' => 'btnSave' ,
			'label' => trans('settings.save_as_inactive'),
			'shape' => 'warning',
			'type' => 'submit' ,
			'value' => 'inactive' ,
		])
		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")'
		])


		@include('forms.group-end')

		@include('forms.feed')

		@include('forms.closer')

	</div>
@include('templates.modal.end')