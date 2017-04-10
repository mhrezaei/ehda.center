@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/users/update/$model->id"
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
</td>


{{--
|--------------------------------------------------------------------------
| Position
|--------------------------------------------------------------------------
|
--}}
<td>
	{{ '' , $roles = $model->roles() }}

	@if($roles->count() > 0) {{-- <~~ when at least one role is defined. --}}
		@foreach($roles->get() as $role)
			@include("manage.frame.widgets.grid-text" , [
				'fake' => $status = $model->as($role->slug)->status ,
				'text' => $role->title . ': ' . trans("forms.status_text.$status"),
				'color' => trans("forms.status_color.$status"),
				'icon' => trans("forms.status_icon.$status"),
				'class' => $model->trashed()? "deleted-content" : '',
				'link' => ($model->is_not_a('dev') and $model->as($role->slug)->canPermit()) ? "modal:manage/users/act/-id-/permits/".$role->id : '',
			])
		@endforeach
	@else  {{-- <~~ when no role is defined. --}}
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans('people.without_role'),
			'color' => "gray",
			'size' => "10",
			'link' => $model->is_not_a('dev')? "modal:manage/users/act/-id-/roles/" : '',
		])
	@endif
</td>

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
	['pencil' , trans('forms.button.edit') , "modal:manage/users/act/-id-/edit" , $model->canEdit()],
	['key' , trans('people.commands.change_password') , "modal:manage/users/act/-id-/password" , !$model->trashed() and $model->canEdit() ] ,
	['shield' , trans('people.user_role') , "modal:manage/users/act/-id-/roles" , $model->is_not_a('dev') and $model->canPermit()],

	['trash', trans('forms.button.delete') , 'modal:manage/users/act/-id-/delete' , !$model->trashed() and $model->canDelete()],
	['undo', trans('forms.button.undelete') , 'modal:manage/users/act/-id-/undelete' , $model->trashed() and $model->canBin()],
	['times' , trans('forms.button.hard_delete') , 'modal:manage/users/act/-id-/destroy' , $model->trashed() and $model->canBin()],

	['user' , trans('people.commands.login_as') , 'modal:manage/users/act/-id-/login_as' , user()->isDeveloper() and !$model->trashed() ] ,
]])