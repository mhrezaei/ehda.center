@include('templates.modal.start' , [
	'form_url' => url('manage/cards/save/printings/'),
	'modal_title' => trans('ehda.printings.revert_to_pending'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['ids' , null ],
		['browse_event_id' , '0' ],
	]])

	@include('forms.input' , [
		'name' => '',
		'id' => 'txtCount' ,
		'label' => trans('validation.attributes.items'),
		'extra' => 'disabled' ,
	])

	@include("forms.note" , [
		'text' => trans('ehda.printings.confirm_good_print_hint'),
		'shape' => "danger" ,
	])

	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('ehda.printings.revert_to_pending'),
			'shape' => 'danger',
			'value' => "revert-to-pending" ,
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
<script>gridSelector('get');$('[name=browse_event_id]').val( $('#txtEventId').val() );</script>