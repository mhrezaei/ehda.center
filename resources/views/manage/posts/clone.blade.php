@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/clone'),
	'modal_title' => trans('posts.form.clone'),
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

	@include("forms.select" , [
		'condition' => $model->has('locales'),
		'name' => "locale",
		'options' => $model->posttype->localesCombo(),
		'value_field' => "0",
		'caption_field' => "1",
		'value' => $model->locale,
	])

	@include("forms.check-form" , [
		'name' => "is_sister",
		'self_label' => trans('posts.form.clone_is_a_sister'),
		'value' => "0",
		'condition' => $model->has('locales'),
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