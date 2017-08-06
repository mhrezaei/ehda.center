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


@include("manage.frame.widgets.grid-text" , [
	'text' => trans("forms.button.count_again"),
	'link' => "divReload('tdCount-$model->id')" ,
	'size' => "10" ,
	'color' => "darkgray" ,
]     )

