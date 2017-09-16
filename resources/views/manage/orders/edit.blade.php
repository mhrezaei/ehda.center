@include("templates.modal.start" , [
	'partial' => "true",
	'form_url' => url('manage/orders/save') ,
	'modal_title' => trans('forms.button.edit').' '.trans('posts.comments.singular') ,
])
<div class='modal-body'>

	{{--
	|--------------------------------------------------------------------------
	| Informative Fields
	|--------------------------------------------------------------------------
	|
	--}}


	@include("forms.hiddens" , [ "fields" => [
		['id' , $model->id],
	]])

	@include("forms.group-start" , [
		'label' => trans('validation.attributes.orderer'),
	])

		<div class="form-control pv10">
			@include("manage.orders.show-orderer")
		</div>

	@include("forms.group-end")


	@include("forms.group-start" , [
		'label' => trans('posts.templates.post'),
	])

	<div class="form-control pv10" style="height: auto">
		@include("manage.orders.show-posts")
	</div>

	@include("forms.group-end")


	{{--
	|--------------------------------------------------------------------------
	| Edit Fields
	|--------------------------------------------------------------------------
	|
	--}}

	@include("forms.select" , [
		'label' => trans('validation.attributes.status'),
		'name' => 'status_name',
		'value' => $model->status_name ,
		'options' => \App\Models\Order::statusCombo() ,
		'value_field' => '0' ,
		'caption_field' => '1' ,
	])

	{{--
	|--------------------------------------------------------------------------
	| Buttons
	|--------------------------------------------------------------------------
	|
	--}}
	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('forms.button.save'),
			'shape' => 'success',
			'type' => 'submit' ,
			'value' => 'save' ,
			'class' => '-delHandle',
		])

		@include('forms.button' , [
			'label' =>  trans('forms.button.cancel') ,
			'shape' => 'link' ,
			'link' => '$(".modal").modal("hide")',
		])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include("templates.modal.end")
