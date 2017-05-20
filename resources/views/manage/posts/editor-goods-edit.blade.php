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
	]])

	@include("forms.select" , [
		'name' => "pack_id",
		'value' => $model->pack_id,
		'options' => $packs,
	])

	@include("forms.input" , [
		'name' => $model->locale=='fa'? 'title' : "_title_in_$model->locale",
		'label' => trans('validation.attributes.title') ,
		'value' => $model->titleIn($model->locale) ,
		'class' =>  in_array($model->locale , ['fa' , 'ar']) ? 'form-required' : 'form-required ltr' ,
	]     )

	@include("forms.input" , [
		'name' => "price",
//		'label' => trans('validation.attributes.original_price'),
		'class' => "form-numberFormat ltr text-center form-required",
		'addon' => setting('currency')->in($model->locale)->gain(),
		'group_class' => "form-group-sm",
		'value' => $model->price,
	])

	@include("forms.input" , [
		'name' => "discount_amount",
//		'top_label' => trans('validation.attributes.discount_amount') ,
		'class' => "form-numberFormat ltr text-center" ,
		'addon' => setting('currency')->in($model->locale)->gain(),
		'group_class' => "form-group-sm",
		'value' => $model->discount_amount,
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