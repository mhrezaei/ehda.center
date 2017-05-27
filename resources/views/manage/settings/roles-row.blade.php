@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/role/-id-",
		'icon' => $model->icon,
	])
</td>


<td>
	@include("manage.frame.widgets.grid-text" , [
		'condition' => $count = $model->users()->count() ,
		'text' => number_format($count) .' '.trans('people.person'),
		'link' => $model->trashed() ? '' : "url:$model->users_browse_link" ,
	]     )
	@include("manage.frame.widgets.grid-text" , [
		'condition' => !$count,
		'text' => trans('forms.general.nobody') ,
		'link' => $model->trashed() ? '' : "url:$model->users_browse_link" ,
	]     )
</td>

<td>
	@include("manage.frame.widgets.grid-badge" , [
		'icon' => trans("forms.status_icon.$model->status"),
		'text' => trans("forms.status_text.$model->status"),
		'link' => 'modal:manage/upstream/edit/role-activeness/-id-' ,
		'color' => trans("forms.status_color.$model->status"),
	])
</td>

@include("manage.frame.widgets.grid-actionCol" , [
	'refresh_action' => false ,
	"actions" => [
		['pencil' , trans('forms.button.edit') , "modal:manage/upstream/edit/role/-id-" ],
		['taxi' , trans('posts.types.locale_titles') , 'modal:manage/upstream/edit/role-titles/-id-' ],
		['trash-o' , trans('forms.button.soft_delete') , 'modal:manage/upstream/edit/role-activeness/-id-' , !$model->trashed()],
		['recycle' , trans('forms.button.undelete') , 'modal:manage/upstream/edit/role-activeness/-id-' , $model->trashed()],
	]
])
