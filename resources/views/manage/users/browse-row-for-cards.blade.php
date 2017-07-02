@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/cards/browse/update/$model->id"
])

{{--
|--------------------------------------------------------------------------
| Name
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->full_name,
		'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/users/act/-id-/card-view" : '',
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans('validation.attributes.card_no').': '.$model->card_no,
		'color' => "gray" ,
		'icon' => "credit-card",
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans("validation.attributes.id").': '.$model->id,
		'color' => "darkgray" ,
		'condition' => user()->isDeveloper() ,
		'icon' => "github-alt" ,
	]     )
</td>

{{--
|--------------------------------------------------------------------------
| Register Time
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'condition' => $model->from_event_id>0,
		'class' => "mv10" ,
		'text' => $model->event ? $model->event->title : '-' ,
	])
	@include("manage.frame.widgets.grid-text" , [
		'fake' => $from_domain_name = $model->from_domain_name,
		'condition' => $from_domain_name ,
		'text' => trans('ehda.from_domain').' '.$from_domain_name ,
		'size' => "11" ,
		'color' => "ulmostblack" ,
		'icon' => "map-marker" ,
	]     )
	@include("manage.frame.widgets.grid-date" , [
		'size' => "11",
		'date' => $model->card_registered_at,
		'color' => "gray",
	])
	{{--@include("manage.frame.widgets.grid-tiny" , [--}}
		{{--'fake' => $domain = $model->say('from_domain'),--}}
		{{--'condition' => $domain != '-',--}}
		{{--'text' => $domain,--}}
	{{--])--}}
</td>


{{--
|--------------------------------------------------------------------------
| City
|--------------------------------------------------------------------------
|
--}}
<td>
	{{ $model->home_city_name }}
</td>

{{--
|--------------------------------------------------------------------------
| Action Button
|--------------------------------------------------------------------------
|
--}}

@include("manage.frame.widgets.grid-actionCol" , [ 'actions' => [
	['pencil' , trans('forms.button.edit') , "modal:manage/users/act/-id-/edit" , $model->canEdit()],
	['key' , trans('people.commands.change_password') , "modal:manage/users/act/-id-/password" , !$model->trashed() and $model->canEdit() ] ,
	['shield' , trans('people.user_role') , "modal:manage/users/act/-id-/roles" , $model->canPermit()],

	['trash', trans('forms.button.delete') , 'modal:manage/users/act/-id-/delete' , !$model->trashed() and $model->canDelete()],
	['undo', trans('forms.button.undelete') , 'modal:manage/users/act/-id-/undelete' , $model->trashed() and $model->canBin()],
	['times' , trans('forms.button.hard_delete') , 'modal:manage/users/act/-id-/destroy' , $model->trashed() and $model->canBin()],

	['user' , trans('people.commands.login_as') , 'modal:manage/users/act/-id-/login_as' , user()->isDeveloper() and !$model->trashed() ] ,
]])