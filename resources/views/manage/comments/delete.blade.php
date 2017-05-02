@include('templates.modal.start' , [
	'form_url' => url('manage/comments/save/delete'),
	'modal_title' => trans('forms.button.soft_delete'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	@include('forms.textarea' , [
		'name' => '',
		'label' => trans('posts.comments.singular'),
		'value' => $model->text ,
		'extra' => 'disabled' ,
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.soft_delete'),
		'shape' => 'danger',
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