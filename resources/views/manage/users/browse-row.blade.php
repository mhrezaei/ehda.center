@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/admins/update/$model->id"
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
		'link' => $model->canEdit()? "modal:manage/users/act/-id-/edit" : '',
	])
</td>



{{--
|--------------------------------------------------------------------------
| Position
|--------------------------------------------------------------------------
| only applicable to admins
--}}
@if($request_role=='admin')
	<td>
		@include("manage.frame.widgets.grid-text" , [
			'text' => $model->admin_position,
			'link' => $model->canPermit()? 'modal:manage/admins/-id-/permit' : '',
		])
	</td>
@endif


{{--
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
|
--}}
<td>
	{{-- when specific role is given.--}}
	@if($request_role!='all')
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans("forms.status_text.". $model->as($request_role)->status),
			'color' => trans("forms.status_color.". $model->as($request_role)->status),
			'icon' => trans("forms.status_icon.". $model->as($request_role)->status),
		])

	{{-- When viewing all users --}}
	@else
		{{ '' , $roles = $model->roles() }}

		@if($roles->count() > 0) {{-- <~~ when at least one role is defined. --}}
			@foreach($roles->get() as $role)
				@include("manage.frame.widgets.grid-text" , [
					'text' => $role->title . ': ' . trans("forms.status_text.". $model->as($request_role)->status),
					'color' => trans("forms.status_color.". $model->as($request_role)->status),
					'icon' => trans("forms.status_icon.". $model->as($request_role)->status),
				])
			@endforeach


		@else  {{-- <~~ when no role is defined. --}}
			@include("manage.frame.widgets.grid-text" , [
				'text' => trans('people.without_role'),
				'color' => "gray",
				'size' => "10",
			])
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
	['pencil' , trans('forms.button.edit') , "modal:manage/users/act/-id-/edit" , $model->as($request_role)->canEdit()],
	['key' , trans('people.commands.change_password') , "modal:manage/users/act/-id-/password" , $model->as($request_role)->canEdit() ] ,
	['shield' , trans('people.commands.permit') , "modal:manage/users/act/-id-/permit" , $model->is_not_a('dev') and $model->canPermit()],

//	['ban' , trans('people.commands.block') , 'modal:manage/users/act/-id-/roles' ,  $model->as($request_role)->canDelete()] ,
//	['undo' , trans('people.commands.unblock') , 'modal:manage/admins/-id-/undelete'  , !$model->as($request_role)->enabled()] ,
//	['times' , trans('forms.button.hard_delete') , 'modal:manage/admins/-id-/destroy' , !$model->as($request_role)->enabled()] ,

	['user' , trans('people.commands.login_as') , 'modal:manage/users/act/-id-/login_as' , user()->isDeveloper() and $model->password] ,
]])