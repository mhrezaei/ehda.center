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

		@include('forms.input' , [
			'name' =>	'icon',
			'class' =>	'ltr',
			'value' =>	$model->icon ,
			'hint' =>	trans('validation.hint.icon_hint'),
		])


		@include("manage.frame.widgets.input-textarea" , [
			'label' => trans('people.modules'),
			'name' => "modules",
			'value' => $model->modules_for_input,
			'class' =>	'ltr form-autoSize',
			'rows' => "3",
		])

		@include("manage.frame.widgets.input-textarea" , [
			'name' => "status_rule",
			'value' => $model->status_rule_for_input ,
			'class' =>	'ltr form-autoSize',
			'rows' => "3",
		]     )

		@include('forms.textarea' , [
			'name' =>	'fields',
			'class' =>	'ltr form-autoSize',
			'rows' => "3",
			'value' =>	$model->fields ,
			'hint' =>	trans('posts.types.meta_hint').' '.implode(' , ',$model::$available_field_types),
		])

		@include("forms.check-form" , [
			'name' => "is_manager",
			'self_label' => trans('people.is_manager') ,
			'value' => $model->is_manager ,
		]     )


		@include('forms.group-start')

			@include('forms.button' , [
				'id' => 'btnSave' ,
				'label' => trans('forms.button.save'),
				'shape' => 'success',
				'type' => 'submit' ,
				'value' => 'save' ,
			])

		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")'
		])
	@include("forms.group-end")

	@include('forms.feed')


	</div>

@include("templates.modal.end")