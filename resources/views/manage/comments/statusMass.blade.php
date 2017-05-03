@include('templates.modal.start' , [
	'form_url' => url('manage/comments/save/statusMass'),
	'modal_title' => trans('forms.button.change_status'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['ids' , null ],
	]])

	@include('forms.input' , [
		'name' => '',
		'id' => 'txtCount' ,
		'label' => trans('validation.attributes.items'),
		'extra' => 'disabled' ,
	])

	@include("forms.select" , [
		'name' => "status",
		'value' => '' ,
		'options' => \App\Models\Comment::first()->statusCombo() ,
		'value_field' => "0" ,
		'caption_field' => "1" ,
		'blank_value' => "" ,
		'class' => "form-required" ,
	]     )


	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('forms.button.save'),
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
<script>gridSelector('get')</script>