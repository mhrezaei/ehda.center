@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/downstream'),
	'modal_title' => $model->id? trans('forms.button.edit') : trans('forms.button.add'),
])
	<div class='modal-body'>

		@include('forms.hidden' , [
			'name' => 'id' ,
			'value' => $model->id,
		])

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
			'name' => 'category' ,
			'class' => 'form-required',
			'options' => $model->categories() ,
			'caption_field' => '1' ,
			'value_field' => '0' ,
			'value' => $model->category ,
			'blank_value' => '' ,
			'blank_label' => '' ,
		])

		@include('forms.select' , [
			'name' => 'data_type' ,
			'class' => 'form-required',
			'options' => $model->dataTypes() ,
			'caption_field' => '1' ,
			'value_field' => '0' ,
			'value' => $model->data_type ,
		])


		@include('forms.group-start')

			@include('forms.check' , [
				'name' => 'developers_only',
				'value' => $model->developers_only,
			])
			@include('forms.check' , [
				'name' => 'is_resident',
				'value' => $model->is_resident,
			])
			@include('forms.check' , [
				'name' => 'is_localized',
				'value' => $model->is_localized,
			])

		@include('forms.group-end')

		@include('forms.group-start')

			@include('forms.button' , [
				'id' => 'btnSave' ,
				'label' => trans('forms.button.save'),
				'shape' => 'success',
				'type' => 'submit' ,
				'value' => 'save' ,
			])
			@if($model->id)
				@include('forms.button' , [
					'id' => 'btnDeleteWarning' ,
					'label' => trans('forms.button.delete'),
					'shape' => 'warning',
					'link' => '$("#btnDelete,#btnDeleteWarning,#btnSave").toggle()' ,
				])
				@include('forms.button' , [
					'id' => 'btnDelete' ,
					'label' => trans('forms.button.sure_hard_delete'),
					'shape' => 'danger',
					'value' => 'delete' ,
					'type' => 'submit' ,
					'class' => 'noDisplay'
				])

			@endif
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