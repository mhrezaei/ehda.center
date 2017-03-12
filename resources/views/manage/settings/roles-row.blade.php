@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/role/-id-",
		'icon' => $model->icon,
	])
</td>


<td>
	{{ pd($model->users()->count()).' '.trans('people.person') }}
</td>

<td>
	@include("manage.frame.widgets.grid-badge" , [
		'icon' => trans("forms.status_icon.$model->status"),
		'text' => trans("forms.status_text.$model->status"),
		'color' => trans("forms.status_color.$model->status"),
	])
</td>

<td>
	@include('templates.say' , ['array'=>json_decode($model->modules,1)])
</td>