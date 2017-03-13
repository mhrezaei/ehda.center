@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/settings/update/$model->id"
])

{{--
|--------------------------------------------------------------------------
| Title
|--------------------------------------------------------------------------
| with an edit link for deelopers
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => user()->isDeveloper()? "modal:manage/upstream/edit/downstream/-id-" : '',
	])
</td>

{{--
|--------------------------------------------------------------------------
| Action
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('forms.button.set'),
		'link' => "modal:manage/settings/act/-id-/downstream-value/",
		'class' => $model->is_set? "btn btn-default" : "btn btn-primary",
		'icon' => "sliders",
	])
</td>
