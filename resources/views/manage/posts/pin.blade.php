@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/pin'),
	'modal_title' => $model->pinned?  trans("posts.pin.remove_command") : trans('posts.pin.put_command'),
])
<div class='modal-body'>

	{{--
	|--------------------------------------------------------------------------
	| id and Name Inputs
	|--------------------------------------------------------------------------
	|
	--}}


	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.title'),
		'value' => $model->title ,
		'extra' => 'disabled' ,
	])




	{{--
	|--------------------------------------------------------------------------
	| Notices
	|--------------------------------------------------------------------------
	|
	--}}


	@include('forms.group-start')

	@include("forms.note" , [
		'text' => trans("posts.pin.description"),
		'shape' => "info" ,
		'condition' => !$model->pinned ,
	]     )

	@include("forms.note" , [
		'text' => trans("posts.pin.put_alert"),
		'shape' => "warning" ,
		'condition' => !$model->pinned ,
	]     )
	
	@include("forms.note" , [
		'text' => trans("posts.pin.remove_alert") ,
		'shape' => "warning" ,
		'condition' => $model->pinned ,
	]     )

	@include('forms.group-end')



	{{--
	|--------------------------------------------------------------------------
	| Buttons
	|--------------------------------------------------------------------------
	|
	--}}


	@include('forms.group-start')

	@include('forms.button' , [
		'label' => $model->pinned?  trans("posts.pin.remove_command") : trans('posts.pin.put_command'),
		'shape' => $model->pinned? 'warning' : 'primary',
		'name' => "submit" ,
		'value' => $model->pinned? 'remove' : 'put' ,
		'type' => 'submit' ,
	])
	@include('forms.button' , [
		'label' => trans('forms.button.cancel'),
		'shape' => 'link',
		'link' => '$(".modal").modal("hide")',
	])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')