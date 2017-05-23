@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/posttype-titles'),
	'modal_title' => trans('posts.types.locale_titles'),
	'no_validation' => true ,
])
<div class='modal-body'>

	@include('forms.hidden' , [
		'name' => 'id' ,
		'value' => $model->spreadMeta()->id,
	])

	@include("forms.input" , [
		'name' => "",
		'label' => trans('validation.attributes.locales') ,
		'disabled' => true ,
		'value' => $model->locales,
	])


	@foreach($model->locales_array as $locale)
		@include("forms.sep" , [
			'label' => trans("forms.lang.$locale"),
			'class' => "text-danger" ,
		]     )

		@include('forms.input' , [
			'name' =>	$locale=='fa'? 'title' : "_title_in_$locale",
			'label' => trans('validation.attributes.title') ,
			'value' =>	$model->titleIn($locale),
			'class' =>  in_array($locale , ['fa' , 'ar']) ? 'form-required' : 'form-required ltr' ,
			'hint' =>	$locale=='fa'? trans('validation.hint.unique').' | '.trans('validation.hint.persian-only') : '',
		])
		@include('forms.input' , [
			'name' =>	$locale=='fa'? 'singular_title' : "_singular_title_in_$locale",
			'label' => trans('validation.attributes.singular_title') ,
			'value' =>	$model->singularTitleIn($locale),
			'class' =>  in_array($locale , ['fa' , 'ar']) ? 'form-required' : 'form-required ltr' ,
			'hint' =>	$locale=='fa'? trans('validation.hint.persian-only') : '',
		])
	@endforeach

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
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")',
		])

	@include('forms.group-end')

	@include('forms.feed')

	@include('forms.closer')

</div>
@include('templates.modal.end')
