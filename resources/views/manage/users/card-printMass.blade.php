@include('templates.modal.start' , [
	'form_url' => url('manage/cards/save/add_to_print_mass'),
	'modal_title' => trans('ehda.printings.send_to'),
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
		'id' => "cmbSendToPrinting",
		'name' => "event_if_for_print" ,
		'options' => model('post')::activeEventsArray() ,
		'value' => session()->get('user_last_used_event') ,
		'label' => trans("validation.attributes.from_event_id") ,
	]     )

	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('ehda.printings.send_to'),
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