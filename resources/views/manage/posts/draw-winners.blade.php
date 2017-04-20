@include('templates.modal.start' , [
	'form_url' => url('manage/club/save/draw_select'),
	'modal_title' => trans('cart.drawing_winners').' '.$model->title,
])
<div class='modal-body'>

	@include("forms.feed" , [
		'div_class' => "m10",
	])


	<div id="divWinnersTable" class="panel panel-default m10">
		@include("manage.posts.draw-winners-table")
	</div>

</div>
@include('templates.modal.end')