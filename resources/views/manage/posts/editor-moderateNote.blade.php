@if($model->isRejected())
	<div class="panel panel-danger">
		<div class="panel-heading">
			<span class="fa fa-meh-o f14"></span>
			{{ trans('validation.attributes.moderate_note') }}
		</div>
		<div class="panel-body bg-warning">
			@include("manage.frame.widgets.grid-text" , [
				'text' => $model->moderate_note,
				'size' => "11" ,
			]     )
			@include("manage.frame.widgets.grid-date" , [
				'date' => $model->moderated_at,
				'text2' => trans('forms.general.by').' '.$model->getPerson('moderated_by')->full_name ,
			]     )

		</div>


	</div>
@endif