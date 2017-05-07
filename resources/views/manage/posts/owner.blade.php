@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/owner'),
	'modal_title' => trans('posts.form.post_owner'),
])
<div class='modal-body'>
	{{ '' , $option? $editor_display_class = '' : $editor_display_class = 'noDisplay' }}
	{{ '' , $option? $viewer_display_class = 'noDisplay' : $viewer_display_class = '' }}
	{{--
	|--------------------------------------------------------------------------
	| Readonly Fields
	|--------------------------------------------------------------------------
	|
	--}}
	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
		['from_editor' , $option],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.title'),
		'value' => $model->title ,
		'extra' => 'disabled' ,
	])

	{{--
	|--------------------------------------------------------------------------
	| Informative Fields
	|--------------------------------------------------------------------------
	|
	--}}
	@include("forms.grid-text" , [
		'label' => trans('posts.form.post_creator') ,
		'text' => $model->creator->full_name,
		'icon' => "user-o" ,
		'link' => "urlN:".$model->creator->profile_link,
	]     )
	@include("forms.grid-text" , [
		'fake' => $owner = $model->getPerson('owned_by') ,
		'label' => trans('posts.form.post_owner') ,
		'text' => $owner->full_name,
		'icon' => "user-o" ,
		'link' => "urlN:".$owner->profile_link,
	]     )



	{{--
	|--------------------------------------------------------------------------
	| Main Field
	|--------------------------------------------------------------------------
	|
	--}}
	@include("forms.sep" , [
		'class' => "ownerEdit $editor_display_class ",
	]     )

	@include("forms.select" , [
		'name' => "owner_id",
		'div_class' => "ownerEdit $editor_display_class " ,
		'value' => $model->owner_id ,
		'label' => trans('posts.form.new_post_owner') ,
		'search' => true ,
		'options' => model('User')::selector([
			'role' => "admin" ,
		])->get() ,
		'caption_field' => "full_name" ,
	]     )

	@include("forms.sep" , [
		'class' => "ownerEdit $editor_display_class ",
	]     )

	{{--
	|--------------------------------------------------------------------------
	| Buttons
	|--------------------------------------------------------------------------
	|
	--}}

	@include('forms.group-start')

	@include("forms.button" , [
		'shape' => "primary" ,
		'class' => "ownerEdit $viewer_display_class" ,
		'type' => "button" ,
		'link' => "$('.ownerEdit').toggle()" ,
		'label' => trans('posts.form.change_post_owner'),
	]     )

	@include('forms.button' , [
		'label' => trans('forms.button.save'),
		'shape' => 'success',
		'class' => "$editor_display_class ownerEdit" ,
		'type' => 'submit' ,
	])
	@include('forms.button' , [
		'label' => trans('forms.button.cancel'),
		'shape' => 'link',
		'link' => '$(".modal").modal("hide")',
		'class' => "$editor_display_class ownerEdit" ,
	])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')