@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/users/update/$model->id/$request_role"
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
//		'link' => $model->canEdit()? "modal:manage/users/act/-id-/edit" : '',
	])
	@include("manage.frame.widgets.grid-text" , [
		'text' => "($model->id)",
		'color' => "gray" ,
		'condition' => user()->isDeveloper() ,
	]     )
</td>

{{--
|--------------------------------------------------------------------------
| Position
|--------------------------------------------------------------------------
|
--}}
@if($request_role=='all')
	<td>
		{{ '' , $roles = $model->withDisabled()->rolesArray() }}

		@if(count($roles))
			@foreach($roles as $role)
				@include("manage.frame.widgets.grid-badge" , [
					'text' => $model->as($role)->title(),
					'color' => $model->as($role)->enabled()? 'success' : 'danger' ,
					'icon' => $model->as($role)->enabled()? 'check' : 'times' ,
					'link' => $model->as($role)->canPermit()? "modal:manage/users/act/-id-/permits/$role" : '',
				]     )
			@endforeach
			@include("manage.frame.widgets.grid-badge" , [
				'text' => trans("people.role_management"),
				'color' => "default" ,
				'icon' => "cog" ,
				'link' => "modal:manage/users/act/-id-/roles/$role" ,
				'condition' => $model->canPermit() ,
			]     )
		@else
			@include("manage.frame.widgets.grid-text" , [
				'text' => trans('people.without_role'),
				'color' => "gray",
				'size' => "10",
				'link' => $model->canPermit()? "modal:manage/users/act/-id-/roles/" : '',
			])
		@endif

	</td>
@endif

{{--
|--------------------------------------------------------------------------
| purchases
|--------------------------------------------------------------------------
|
--}}
@if($request_role=='customer')
	<td>
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans('cart.no_receipt'),
			'condition' => $model->total_receipts_count == 0,
			'color' => "gray",
			'link' => "modal:manage/users/act/-id-/receipts",
		])
		@include("manage.frame.widgets.grid-text" , [
			'text' => $model->total_receipts_count . " " . trans('cart.receipt') . " (" . number_format($model->total_receipts_amount/10) . ' ' . setting()->ask('currency')->grab() . ') ' ,
			'condition' => $model->total_receipts_count  > 0,
			'link' => "modal:manage/users/act/-id-/receipts",
		])
	</td>
@endif

{{--
|--------------------------------------------------------------------------
| Action Button
|--------------------------------------------------------------------------
|
--}}

@include("manage.frame.widgets.grid-actionCol" , [ 'actions' => [
//	['pencil' , trans('forms.button.edit') , "modal:manage/users/act/-id-/edit" , $model->canEdit()],
//	['key' , trans('people.commands.change_password') , "modal:manage/users/act/-id-/password" , !$model->trashed() and $model->canEdit() ] ,
	['shield' , trans('people.user_role') , "modal:manage/users/act/-id-/roles" , $model->canPermit()],

	['trash', trans('forms.button.delete') , 'modal:manage/users/act/-id-/delete' , !$model->trashed() and $model->canDelete()],
	['undo', trans('forms.button.undelete') , 'modal:manage/users/act/-id-/undelete' , $model->trashed() and $model->canBin()],
	['times' , trans('forms.button.hard_delete') , 'modal:manage/users/act/-id-/destroy' , $model->trashed() and $model->canBin()],

	['user' , trans('people.commands.login_as') , 'modal:manage/users/act/-id-/login_as' , user()->isDeveloper() and !$model->trashed() ] ,
]])