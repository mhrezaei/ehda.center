@foreach($model->withDisabled()->rolesQuery() as $role)


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

	@include("manage.frame.widgets.grid-badge" , [
		'condition' => $role['is_admin'],
		'text' => $role['title'] ,
		'color' => $color ,
		'icon' => $icon ,
		'link' => $model->as($role['slug'])->canPermit()? "modal:manage/users/act/-id-/permits/".$role['slug'] : v0(),
	]     )


@endforeach


@include("manage.frame.widgets.grid-badge" , [
	'text' => trans("people.role_management"),
	'color' => "default" ,
	'icon' => "cog" ,
	'link' => "modal:manage/users/act/-id-/roles/" ,
	'condition' => $model->canPermit() ,
]     )

