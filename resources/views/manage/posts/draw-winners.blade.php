@include('templates.modal.start' , [
	'form_url' => url('manage/club/save/draw_select'),
	'modal_title' => trans('cart.drawing_winners').' '.$model->title,
])
<div class='modal-body'>

	<div id="divWinnersTable" class="panel panel-default m10">
		@include("manage.posts.draw-winners-table")
	</div>

</div>

@if(!$model->isDrawingReady())
	<div class="modal-footer">
		@include("forms.button" , [
			'label' => trans('cart.redraw_prepare'),
			'shape' => "primary",
			'link' => "masterModal(url('manage/posts/act/$model->id/draw'))",
		])
	</div>
@endif

@include('templates.modal.end')