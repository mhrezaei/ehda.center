@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/admins/update/$model->id"
])

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->full_name,
		'link' => "modal:manage/admins/-id-/edit",
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->admin_position,
		'link' => $model->canPermit()? 'modal:manage/admins/-id-/permit' : '',
	])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("forms.status_text.". $model->as('admin')->status),
		'color' => trans("forms.status_color.". $model->as('admin')->status),
		'icon' => trans("forms.status_icon.". $model->as('admin')->status),
	])
</td>

@include("manage.frame.widgets.grid-actionCol" , [ 'actions' => [
	['pencil' , trans('forms.button.edit') , "modal:manage/admins/-id-/edit" , $model->as('admin')->canEdit()],
	['key' , trans('people.commands.change_password') , "modal:manage/admins/-id-/password" , $model->as('admin')->canEdit() ] ,
	['shield' , trans('people.commands.permit') , "modal:manage/admins/-id-/permit" , $model->as('admin')->canPermit()],

	['ban' , trans('people.commands.block') , 'modal:manage/admins/-id-/soft_delete' , $model->as('admin')->canDelete()] ,
	['undo' , trans('people.commands.unblock') , 'modal:manage/admins/-id-/undelete'  , $model->trashed()] ,
	['times' , trans('forms.button.hard_delete') , 'modal:manage/admins/-id-/hard_delete' , $model->trashed()] ,

	['user' , trans('people.commands.login_as') , 'modal:manage/admins/-id-/login_as' , user()->isDeveloper() and !$model->trashed()] ,
]])