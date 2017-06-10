@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/city/-id-",
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->province()->title,
		'link' => "modal:manage/upstream/edit/state/".$model->province()->id,
	])
</td>

<td>
	@if($model->domain_id)
		@include("manage.frame.widgets.grid-text" , [
			'text' => $model->domain->title,
			'link' => "modal:manage/upstream/edit/domain/".$model->domain_id,
		])
	@else
		-
	@endif
</td>

<td>
	@include("manage.frame.widgets.grid-badge" , [
		'condition' => $model->isCapital(),
		'icon' => "certificate",
		'text' => trans('validation.attributes.capital_id'),
		'color' => "success",
		'opacity' => "0.8",
	])
</td>




