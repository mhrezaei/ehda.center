@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/state/-id-",
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->capital()->title,
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->cities()->count().' '.trans('settings.city'),
		'link' => "url:manage/upstream/states/-id-",
	])
</td>