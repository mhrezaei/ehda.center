<td>
	@include('manage.frame.widgets.grid-action' , [
		'id' => $model->id ,
		'fake' => !isset($refresh_action) ? $refresh_action = true : '' ,
		'fake' => $refresh_action ? array_unshift($actions ,
			['retweet' , trans('forms.button.refresh') , "rowUpdate('auto','$model->id')" ]
		) : false ,
	])
</td>