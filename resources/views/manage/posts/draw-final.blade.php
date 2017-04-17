@include('templates.modal.start' , [
	'fake' => $model->prepareForDrawing(),
	'form_url' => url('manage/posts/save/draw_prepare'),
	'modal_title' => trans('cart.draw'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])


	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')