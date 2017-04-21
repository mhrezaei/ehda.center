@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/locales'),
	'modal_title' => trans('posts.features.locales'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.title'),
		'value' => $model->title ,
		'extra' => 'disabled' ,
	])

	@include('forms.group-start')

		<div style="width: 50%">
			@include("manage.posts.editor-locales-inside")
		</div>

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')