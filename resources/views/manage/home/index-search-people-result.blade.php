{{--
|--------------------------------------------------------------------------
| When nothing found...
|--------------------------------------------------------------------------
|
--}}

@if($total_found == 0)
	@include("manage.frame.widgets.grid-text" , [
		'text' => $field=='code_melli' ? trans("people.code_melli_not_found") : trans("people.nobody_found"),
		'color' => "danger" ,
		'icon' => "exclamation-triangle" ,
	]     )

	@if($field=='code_melli')
		<div class="w100 text-center">
			@if(user()->as('admin')->can('users-card-holder.create'))
				<a href="{{ url("manage/cards/create/$keyword") }}"
				   class="btn btn-default w70">{{ trans("ehda.cards.create") }}</a>
			@endif
			@if( user()->userRolesArray('create' , [] , model('role')::adminRoles()) )
				<a href="{{ url("manage/volunteers/create/admin/$keyword") }}"
				   class="btn btn-default w70">{{ trans("ehda.volunteers.create") }}</a>
			@endif
		</div>
	@endif
@endif



{{--
|--------------------------------------------------------------------------
| When at least one record is found...
|--------------------------------------------------------------------------
|
--}}
@if($total_found > 0)
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->full_name,
		'color' => "success" ,
		'icon' => "check" ,
		'size' => "14" ,
	]     )

	@if($model->is_a('card-holder'))
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans("ehda.donation_card"),
			'icon' => "credit-card" ,
			'color' => "success" ,
			'class' => "btn btn-default w70" ,
			'size' => "12" ,
			'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/cards/view/-hash_id-" : v0() ,
		]     )
	@elseif(user()->can('users-card-holder.create'))
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans("ehda.cards.register_full"),
			'icon' => "credit-card" ,
			'class' => "btn btn-default w70" ,
			'size' => "12" ,
			'link' => "url:manage/cards/create/$model->code_melli" ,
		]     )
	@endif

	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("ehda.volunteers.single"),
		'icon' => "child" ,
		'color' => "success" ,
		'class' => "btn btn-default w70" ,
		'size' => "12" ,
		'link' => user()->as('admin')->can('volunteer')? "modal:manage/volunteers/view/-hash_id-" : v0(),
		'condition' => $model->is_admin() ,
	]     )

	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("people.role_management"),
		'icon' => "cog" ,
		'class' => "btn btn-default w70" ,
		'size' => "12" ,
		'link' => "modal:manage/users/act/-id-/roles/" ,
		'condition' => user()->isDeveloper() , //$model->canPermit() ,
	]     )


	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("people.commands.login_as") ,
		'icon' => "sign-in" ,
		'class' => "btn btn-info w70" ,
		'size' => "12" ,
		'link' => 'modal:manage/users/act/-id-/login_as' ,
		'condition' => user()->isDeveloper() and !$model->trashed() ,
	]     )

	@if(user()->isDeveloper())
		<div class="f10 ltr">
			id: {{$model->id}}
		</div>
	@endif

@endif


{{--
|--------------------------------------------------------------------------
| More than one instnce
|--------------------------------------------------------------------------
|
--}}
@if($total_found > 1)
	<div style="margin-top: 10px">
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans("forms.feed.all_count_search_results" , [
				'count' => $total_found ,
			]),
			'size' => "10" ,
			'link' => url("manage/users/browse/all/search?keyword=$keyword&searched=1") ,
			'icon' => "caret-left" ,
			'condition' => user()->as('admin')->can('users-all') ,
		]     )
	</div>
@endif


