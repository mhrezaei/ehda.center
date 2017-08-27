@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/students/browse/update/$model->id"
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
		'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/cards/view/-hash_id-" : '',
	])

	@include("manage.frame.widgets.grid-badge" , [
		'condition' => $model->is_a('card-holder') ,
		'text' => trans("ehda.donation_card") ,
		'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/cards/view/-hash_id-" : '',
		'size' => "9" ,
		'icon' => "credit-card" ,
		'color' => "success" ,
	]     )

	@include("manage.frame.widgets.grid-badge" , [
		'text' => trans("ehda.volunteers.single"),
		'icon' => "child" ,
		'color' => "info" ,
		'condition' => $model->is_admin() ,
		'link' => user()->as('admin')->can("user-volunteer")? "modal:manage/volunteers/view/$model->hash_id" : "" ,
	]     )

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
	@include("manage.frame.widgets.grid-date" , [
		'size' => "11",
		'date' => $model->card_registered_at,
		'color' => "gray",
	])
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
	['key' , trans('people.commands.change_password') , "modal:manage/users/act/-id-/password" , !$model->trashed() and $model->canEdit() ] ,

//	['trash', trans('forms.button.delete') , 'modal:manage/users/act/-id-/delete' , !$model->trashed() and $model->canDelete()],
//	['undo', trans(//'forms.button.undelete') , 'modal:manage/users/act/-id-/undelete' , $model->trashed() and $model->canBin()],
//	['times' , trans('forms.button.hard_delete') , 'modal:manage/users/act/-id-/destroy' , $model->trashed() and $model->canBin()],

	['user' , trans('people.commands.login_as') , 'modal:manage/users/act/-id-/login_as' , user()->isDeveloper() and !$model->trashed() ] ,
]])