@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/posttype'),
	'modal_title' => $model->id? trans('forms.button.edit') : trans('forms.button.add'),
	'no_validation' => true ,
])
<div class='modal-body'>

	@include('forms.hidden' , [
		'name' => 'id' ,
		'value' => $model->spreadMeta()->id,
	])

	@include('forms.input' , [
		'name' =>	'slug',
		'class' =>	'form-required ltr form-default' ,
		'value' =>	$model->slug ,
		'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.english-only') . ' | '.trans('validation.hint.no_underline'),
	])

	@include('forms.input' , [
	    'name' =>	'title',
	    'value' =>	$model->title,
	    'class' => 'form-required' ,
	    'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
	])
	@include('forms.input' , [
	    'name' =>	'singular_title',
	    'value' =>	$model->singular_title,
	    'class' => 'form-required' ,
	    'hint' =>	trans('validation.hint.persian-only'),
	])

	@include("forms.input" , [
		'name' => "order",
		'class' => "",
		'value' => $model,
	])

	@include('forms.input' , [
	    'name' =>	'icon',
	    'class' =>	'form-required ltr',
		'value' =>	$model->icon ,
	    'hint' =>	trans('validation.hint.icon_hint'),
	])

	@include('forms.input' , [
		'name' =>	'header_title',
		'value' =>	$model->header_title,
		'hint' =>	trans('validation.hint.persian-only'),
	])

	@include("forms.select" , [
		'name' => "template",
		'class' => "form-required",
		'options' => $model->templatesCombo(),
		'caption_field' => "1",
		'value_field' => "0",
		'value' => $model->template,
	])

	@include('forms.textarea' , [
		'id' => "txtMeta",
		'name' =>	'feature_meta',
		'class' =>	'ltr form-autoSize',
		'rows' => "3",
		'value' =>	$model->feature_meta. ' ' ,
		'extra' => "readonly",
	])
	@include('forms.textarea' , [
		'name' =>	'optional_meta',
		'class' =>	'ltr form-autoSize',
		'rows' => "3",
		'value' =>	$model->optional_meta ,
		'hint' =>	trans('posts.types.meta_hint').' '.implode(' , ',$model::$available_meta_types),
	])

	@include("forms.group-start" , [
		'label' => trans('posts.features.meaning'),
	])

		@include("forms.input-self" , [
			'id' => "txtFeatures",
			'name' => "features",
			'value' => $model->features,
			'type' => "hidden",
		])

		@foreach(json_decode($model->available_features) as $feature => $para)
			@include("manage.frame.widgets.grid-badge" , [
				'id' => "lblFeature-$feature",
				'color' => $para[1],
				'icon' => $para[0],
				'text' => trans("posts.features.$feature"),
				'opacity' => in_array($feature , $model->features_array)? "0.9" : "0.3",
				'link' => "posttypeFeatures('$feature')",
			])
		@endforeach

	@include("forms.group-end")

	@if($model->id and $posts = $model->posts()->count())
		@include('forms.note' , [
			'shape' => 'warning' ,
			'text' => trans('posts.types.delete_alert_posts' , ['count' => $posts]) ,
			'class' => '-delHandle noDisplay'
		])
	@endif


	@include('forms.note' , [
		'shape' => 'danger' ,
		'text' => trans('posts.types.delete_alert') ,
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
				'class' => '-delHandle' ,
			])
			@include('forms.button' , [
				'id' => 'btnDelete' ,
				'label' => trans('forms.button.sure_delete'),
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
<script>
	window.available_features = {!! $model->available_features !!}
</script>