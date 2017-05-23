@include('templates.modal.start' , [
	'partial' => true ,
	'form_url' => url('manage/settings/save/'),
	'modal_title' => $model->title ,
])
	<div class='modal-body'>

		@include('forms.hiddens' , ['fields' => [
			['id' , $model->id],
		]])

		@include("forms.note" , [
			'text' => $model->hint,
			'condition' => boolval($model->hint),
		]     )

		@include('forms.input' , [
			'name' =>	'title',
			'value' =>	$model->title." ($model->slug) ",
			'extra' => 'disabled'
		])

		@include("manage.frame.widgets.input-$model->data_type" , [
			'condition' => user()->isDeveloper(),
			'value' => $model->defaultValue()->nocache()->raw()->gain() ,
			'name' => 'default_value' ,
			'label' => trans('validation.attributes.default_value'),
			'class' => 'form-default '.$model->css_class ,
		])

		@if($model->is_localized)
			@foreach(setting('site_locales')->nocache()->gain() as $lang)
				@include("manage.frame.widgets.input-$model->data_type" , [
					'value' => $model->reset()->nocache()->raw()->in($lang)->gain() ,
					'name' => $lang,
					'label' => trans("forms.lang.$lang"),
					'class' => $model->css_class ,
				])
			@endforeach
		@else
			@include("manage.frame.widgets.input-$model->data_type" , [
				'value' => $model->reset()->nocache()->raw()->gain() ,
				'name' => 'custom_value',
				'class' => $model->css_class ,
			])
		@endif

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


		@include('forms.group-end')

		@include('forms.feed')

		@include('forms.closer')

	</div>
@include('templates.modal.end')