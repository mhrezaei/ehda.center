@include('templates.modal.start' , [
	'form_url' => url('manage/comments/save/undeleteMass'),
	'modal_title' => trans('forms.button.undelete'),
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

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.undelete'),
		'shape' => 'warning',
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