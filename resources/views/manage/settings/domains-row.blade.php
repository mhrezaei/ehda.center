@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/domain/-id-",
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->slug,
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->alias,
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->states()->count().' '.trans('settings.city'),
		'link' => "url:manage/upstream/domains/-id-",
	])
</td>