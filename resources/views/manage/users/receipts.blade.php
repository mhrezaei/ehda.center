@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/receipt'),
	'modal_title' => trans('cart.receipts').' '.$model->full_name,
])
	@include("forms.feed" , [
		'div_class' => "m10",
	])

	<div id="divReceiptsTable" class="panel panel-default m10">
		@include("manage.users.receipts-table")
	</div>

@include("templates.modal.end")