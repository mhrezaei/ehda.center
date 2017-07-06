@include('templates.modal.start' , [
	'form_url' => url('manage/cards/save/printings/'),
	'modal_title' => trans('manage.permissions.print-direct'),
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

	@include('forms.group-start')

	@include("forms.check" , [
		'name' => "select_all",
		'label' => trans('ehda.printings.select_all_from_pendings'),
		'value' => false,
	])
	@include('forms.group-end')

	@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('manage.permissions.print-direct'),
			'shape' => 'primary',
			'value' => "add-to-direct" ,
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