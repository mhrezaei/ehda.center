@include('templates.modal.start' , [
	'fake' => $role = model('role')::findBySlug($option) ,
	'form_url' => url('manage/users/save/statusMass'),
	'modal_title' => trans('forms.button.change_status'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['ids' , null ],
		['role_id' , $role->hashid]
	]])

	@include('forms.input' , [
		'name' => '',
		'id' => 'txtCount' ,
		'label' => trans('validation.attributes.items'),
		'extra' => 'disabled' ,
	])

	@include("forms.select" , [
		'name' => "new_status" ,
		'options' => $role->statusCombo() ,
		'value' => null ,
		'class' => "form-required" ,
		'blank_value' => "" ,
		'blank_label' => "" ,
		'caption_field' => "1" ,
		'value_field' => "0" ,
	]     )

	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('forms.button.change_status'),
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