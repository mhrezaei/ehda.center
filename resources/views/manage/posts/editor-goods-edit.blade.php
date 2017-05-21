@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/good'),
	'modal_title' => $model->id? trans('cart.goods.edit') : trans('cart.goods.create'),
	'no_validation' => true ,
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id],
		['type' , $model->type],
		['sisterhood' , $model->sisterhood] ,
		['post_id' , $model->post_id] ,
		['_locale' , $locale] ,
	]])

	{{--
	|--------------------------------------------------------------------------
	| Main Form Items
	|--------------------------------------------------------------------------
	|
	--}}

	@include("forms.input" , [
		'name' => $locale=='fa'? 'title' : "_title_in_$locale",
		'label' => trans('validation.attributes.title').' '.trans("forms.lang.$locale") ,
		'value' => $model->titleIn($locale) ,
		'class' =>  in_array($locale , ['fa' , 'ar']) ? 'form-default' : 'form-default ltr' ,
		'hint' => trans("cart.goods.what_if_no_title") ,
	]     )


	@if(isset($packs))
		@include("forms.select" , [
			'name' => "pack_id",
			'value' => $model->pack_id,
			'blank_value' => "0" ,
			'options' => $packs,
		])
	@endif

	@if(count($colors_combo = $model->colorsCombo()))
		@include("forms.select" , [
			'name' => "color",
			'value' => $model->color ,
			'blank_value' => "" ,
			'options' => $colors_combo ,
			'value_field' => "0" ,
			'caption_field' => '1' ,
		]     )
	@endif

	@include("forms.input" , [
		'name' => "price",
//		'label' => trans('validation.attributes.original_price'),
		'class' => "form-numberFormat ltr text-center form-required",
		'addon' => setting('currency')->in($locale)->gain(),
		'group_class' => "form-group-sm",
		'value' => $model->price,
	])

	@include("forms.input" , [
		'name' => "discount_amount",
//		'top_label' => trans('validation.attributes.discount_amount') ,
		'class' => "form-numberFormat ltr text-center" ,
		'addon' => setting('currency')->in($locale)->gain(),
		'group_class' => "form-group-sm",
		'value' => $model->discount_amount,
	]     )

	@include("forms.check-form" , [
		'name' => "_is_disabled",
		'value' => !$model->isAvailableIn($locale) ,
		'self_label' => trans('cart.goods.deactivate_for_now') ,
	]     )

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