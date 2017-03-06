@if(false and $model->has('visibility_choice'))

	@include("forms.select_self" , [
		'top_label' => trans('posts.visibility.title'),
		'name' => "visibility",
		'options' => $model->visibilityCombo(),
		'value' => $model->template,
		'value_field' => "0",
		'caption_field' => "1",
	])


@endif