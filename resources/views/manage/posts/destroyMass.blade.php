@include('templates.modal.start' , [
	'form_url' => url('manage/posts/save/destroyMass'),
	'modal_title' => trans('forms.button.hard_delete'),
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

	@include("forms.note" , [
		'text' => trans('people.form.hard_delete_notice'),
		'shape' => "warning",
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.sure_hard_delete'),
		'shape' => 'danger',
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