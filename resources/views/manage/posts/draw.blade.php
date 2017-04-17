@include('templates.modal.start' , [
	'fake' => $model->prepareForDrawing(),
	'form_url' => url('manage/posts/save/draw_prepare'),
	'modal_title' => trans('cart.draw'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('posts.features.event'),
		'value' => $model->title ,
		'extra' => 'disabled' ,
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'id' => "btnPrepare",
		'label' => trans('cart.draw_prepare'),
		'shape' => 'primary',
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