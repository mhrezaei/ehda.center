@include('manage.frame.widgets.grid-rowHeader')

{{--
|--------------------------------------------------------------------------
| Title
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title . " ($model->slug) ",
	])
</td>

{{--
|--------------------------------------------------------------------------
| continuity...
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('settings.continuous'),
		'icon' => "check",
		'color' => "success",
		'condition' => $model->is_continuous,
	])
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('settings.discontinuous'),
		'icon' => "times",
		'color' => "warning",
		'condition' => !$model->is_continuous,
	])
</td>

{{--
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
| 
--}}
<td>
	@include("manage.frame.widgets.grid-badge" , [
		'text' => trans("forms.status_text.$model->status"),
		'icon' => trans("forms.status_icon.$model->status"),
		'color' => trans("forms.status_color.$model->status"),
	])
</td>


{{--
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('forms.button.set'),
		'link' => "modal:manage/upstream/edit/package/-id-",
		'class' => "btn btn-default",
		'icon' => "eye",
	])
</td>