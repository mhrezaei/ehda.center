@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/volunteers/browse/update/$model->id/$request_role"
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
		'size' => "14" ,
		'link' => "modal:manage/volunteers/view/-hash_id-" ,
//		'link-' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/users/act/-id-/card-view" : '',
	])

	{{-- Donation Card --}}
	@if($model->card_registered_at)
		@include("manage.frame.widgets.grid-badge" , [
			'text' => trans("ehda.donation_card") ,
			'color' => "default" ,
			'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/cards/view/-hash_id-" : '',
			'size' => "9" ,
			'icon' => "credit-card" ,
			'class' => "text-success" ,
		]     )
	@else
		@include("manage.frame.widgets.grid-badge" , [
			'text' => trans("ehda.without_donation_card") ,
			'color' => "danger" ,
			'icon' => "credit-card" ,
			'size' => "9" ,
		]     )
	@endif

	{{-- ID --}}
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans("validation.attributes.id").': '.$model->id,
		'color' => "darkgray" ,
		'condition' => user()->isDeveloper() ,
		'icon' => "github-alt" ,
		'size' => "9" ,
		'locale' => "en" ,
	]     )
</td>

{{--
|--------------------------------------------------------------------------
| City & Occupation 
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->occupation,
		'div_class' => "mv5" ,
	]     )
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => $model->home_city_name,
		'icon' => "map-marker" ,
		'div_class' => "mv10" ,
	]     )
</td>


{{--
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
|
--}}
<td>

	@if($request_role == 'admin')

		@include("manage.users.volunteer-view-roles")

	@else
		@if( $model->as($request_role)->enabled() )
			{{ '' , $status =  $role->statusRule( $model->as($request_role)->status() )}}
			@include("manage.frame.widgets.grid-text" , [
				'text' => trans("people.criteria.$status") ,
				'color' => trans("people.criteria_color.$status") ,
				'icon' => trans("people.criteria_icon.$status") ,
				'link' => $model->as($request_role)->canEdit()? "modal:manage/users/act/-id-/user-status/$request_role" : '' ,
			]     )
			@include("manage.frame.widgets.grid-text" , [
				'text' => trans("people.commands.permit"),
				'class' => "btn btn-default" ,
				'size' => "10" ,
				'link' => $model->as($request_role)->canPermit()? "modal:manage/users/act/-id-/permits/$request_role" : '',
				'icon' => "shield" ,
				'condition' => $model->as($request_role)->canPermit() ,
			]     )
		@else
			@include("manage.frame.widgets.grid-text" , [
				'text' => trans("people.criteria.banned"),
				'color' => "danger" ,
				'icon' => "times" ,
			]     )
		@endif
	@endif

</td>

{{--
|--------------------------------------------------------------------------
| Action Button
|--------------------------------------------------------------------------
|
--}}

@include("manage.frame.widgets.grid-actionCol" , [ 'actions' => [
	['pencil' , trans('forms.button.edit') , "url:manage/volunteers/edit/-hash_id-" , $model->canEdit()],
	['key' , trans('people.commands.change_password') , "modal:manage/users/act/-id-/password" , !$model->trashed() and $model->canEdit() ] ,
	['gavel', trans('forms.button.change_status'), "modal:manage/users/act/-id-/user-status/".$request_role , $request_role != 'admin' and $model->canEdit()],

//	['ban', trans('forms.button.delete') , "modal:manage/users/act/-id-/ban/$request_role" , !$model->trashed() and $model->canDelete()],
//	['undo', trans(//'forms.button.undelete') , 'modal:manage/users/act/-id-/undelete' , $model->trashed() and $model->canBin()],
//	['times' , trans('forms.button.hard_delete') , 'modal:manage/users/act/-id-/destroy' , $model->trashed() and $model->canBin()],

	['user' , trans('people.commands.login_as') , 'modal:manage/users/act/-id-/login_as' , user()->isDeveloper() and !$model->trashed() ] ,
]])