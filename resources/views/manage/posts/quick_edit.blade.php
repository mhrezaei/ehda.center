@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/quick'),
	'modal_title' => trans('posts.form.quick_edit'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	{{--
	|--------------------------------------------------------------------------
	| Title
	|--------------------------------------------------------------------------
	| short title and long title, based on the Posttype
	--}}

	@include('forms.input' , [
		'name' => 'title',
		'condition' => $model->hasnot('long_title'),
		'class' => "form-default" ,
		'value' => $model->title ,
	])
	@include("forms.textarea" , [
		'name' => "title",
		'value' => $model->title,
		'condition' => $model->has('long_title'),
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('posts.form.make_a_clone_and_get_me_there'),
		'shape' => 'primary',
		'type' => 'submit' ,
		'value' => "redirect_after_save",
	])
	@include('forms.button' , [
		'label' => trans('posts.form.make_a_clone_and_save_to_drafts'),
		'shape' => 'default',
		'type' => 'submit' ,
		'value' => "just_save",
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