{{--
|--------------------------------------------------------------------------
| Admin Roles
|--------------------------------------------------------------------------
|
--}}

{{ '' , $support_roles = [] }}
@foreach($model->as('all')->withDisabled()->rolesQuery() as $role)

	@if($role['pivot']['deleted_at'])
		{{ '' , $color = 'danger' }}
		{{ '' , $icon = 'times '}}
	@elseif($role['pivot']['status'] >= 8)
		{{ '' , $color = 'success' }}
		{{ '' , $icon = 'check '}}
	@else
		{{ '' , $color = 'default' }}
		{{ '' , $icon = 'hourglass-half'}}
	@endif

	@if(str_contains($role['slug'] , 'support-'))
		{{ '' , $support_roles[] = $role }}
	@endif

	@include("manage.frame.widgets.grid-badge" , [
		'condition' => $role['is_admin'],
		'text' => $role['title'] ,
		'color' => $color ,
		'icon' => $icon ,
		'link' => $model->as($role['slug'])->canPermit()? "modal:manage/users/act/-id-/permits/".$role['slug'] : v0(),
	]     )


@endforeach




{{--
|--------------------------------------------------------------------------
| Support Roles
|--------------------------------------------------------------------------
| Uses the $support_roles arrray, genereated in the previous loop
--}}

@foreach( $support_roles as $support_role)
	@include("manage.frame.widgets.grid-badge" , [
		'text' => $support_role['title'],
		'color' => "info" ,
		'icon' => 'ambulance' ,
	]     )
@endforeach


{{--
|--------------------------------------------------------------------------
| Full Role Management
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.grid-badge" , [
	'text' => trans("people.role_management"),
	'color' => "default" ,
	'icon' => "cog" ,
	'link' => "modal:manage/users/act/-id-/roles/" ,
	'condition' => user()->isDeveloper() , //$model->canPermit() ,
]     )

