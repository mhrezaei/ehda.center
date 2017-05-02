@include("templates.modal.start" , [
	'partial' => "true",
	'form_url' => url('manage/comments/save') ,
	'modal_title' => trans('forms.button.edit').' '.trans('posts.comments.singular') ,
]     )
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
		'label' => trans('validation.attributes.sender'),
	])

		<div class="form-control pv10">
			@include("manage.comments.show-sender")
		</div>

	@include("forms.group-end")


	@include("forms.group-start" , [
		'label' => trans('posts.templates.post'),
	])

	<div class="form-control pv10">
		@include("manage.comments.show-post")
	</div>

	@include("forms.group-end")


	{{--
	|--------------------------------------------------------------------------
	| Edit Fields
	|--------------------------------------------------------------------------
	|
	--}}

	@include("forms.select" , [
		'name' => "status",
		'value' => $model->status ,
		'options' => $model->statusCombo() ,
		'value_field' => "0" ,
		'caption_field' => "1" ,
	]     )

	@include("forms.textarea" , [
		'name' => "text",
		'class' => "form-autoSize form-default form-required" ,
		'rows' => "3" ,
		'value' => $model->text ,
	]     )


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

		@include("forms.button" , [
			'condition' => $child_count = $model->parent()->children()->count() ,
			'label' => trans("posts.comments.dialogue_with_number" , [	"number" => pd($child_count),]),
			'shape' => "link" ,
			'link' => "masterModal(url('manage/comments/act/$model->id/show'))" ,
		]     )

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include("templates.modal.end")
