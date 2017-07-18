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
				<a href="{{ url("manage/cards/create/$keyword") }}" class="btn btn-default w70">{{ trans("ehda.cards.create") }}</a>
			@endif
			@if(user()->as('admin')->can_any(model('role')::adminRoles('.create')))
				<a href="{{ url("manage/volunteers/create/admin/$keyword") }}" class="btn btn-default w70">{{ trans("ehda.volunteers.create") }}</a>
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

	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("ehda.donation_card"),
		'icon' => "credit-card" ,
		'color' => "success" ,
		'class' => "btn btn-default w70" ,
		'size' => "12" ,
		'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/users/act/$model->id/card-view" : v0() ,
		'condition' => $model->is_a('card-holder') ,
	]     )

	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("ehda.volunteers.single"),
		'icon' => "child" ,
		'color' => "success" ,
		'class' => "btn btn-default w70" ,
		'size' => "12" ,
		'link' => v0() ,
		'condition' => $model->is_admin() ,
	]     )



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


