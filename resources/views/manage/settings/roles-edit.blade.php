@include("templates.modal.start" , [
	'form_url' => url('manage/upstream/save/role'),
	'modal_title' => $model->id? trans('forms.button.edit') : trans('forms.button.add'),
])

	<div class="modal-body">
		@include('forms.hidden' , [
			'name' => 'id' ,
			'value' => $model->id,
		])

		@include('forms.input' , [
			'name' =>	'slug',
			'class' =>	'form-required ltr form-default',
			'value' =>	$model ,
			'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.english-only'),
		])


		@include('forms.input' , [
			'name' =>	'title',
			'value' =>	$model,
			'class' => 'form-required' ,
			'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
		])

		@include('forms.input' , [
			'name' =>	'plural_title',
			'value' =>	$model,
			'class' => 'form-required' ,
			'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
		])

		@include("manage.frame.widgets.input-textarea" , [
			'label' => trans('people.modules'),
			'name' => "modules",
			'value' => $model->modules,
			'class' => "ltr",
		])


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
					'condition' => !$model->trashed(),
					'label' => trans('forms.button.deactivate_only'),
					'shape' => 'danger',
					'value' => 'delete' ,
					'type' => 'submit' ,
				])
				@include('forms.button' , [
					'condition' => $model->trashed(),
					'label' => trans('forms.button.activate_only'),
					'shape' => 'primary',
					'value' => 'restore' ,
					'type' => 'submit' ,
				])

		@endif

		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")'
		])
	@include("forms.group-end")

	@include('forms.feed')


	</div>

@include("templates.modal.end")