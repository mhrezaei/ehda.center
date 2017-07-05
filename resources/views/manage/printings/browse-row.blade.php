@include('manage.frame.widgets.grid-rowHeader' )

{{--
|--------------------------------------------------------------------------
| Name
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'fake' => $user = $model->user  ,
		'text' => $user->full_name ,
		'link' => ($user->id and user()->as('admin')->can('users-card-holder.view'))? "modal:manage/users/act/$user->id/card-view" : '',
		'link-' => "modal:manage/users/act/$user->id/card-view"
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans('validation.attributes.card_no').': '.$user->card_no,
		'color' => "gray" ,
		'icon' => "credit-card",
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans("validation.attributes.id").': '.$user->id,
		'color' => "darkgray" ,
		'condition' => user()->isDeveloper() ,
		'icon' => "github-alt" ,
	]     )

</td>

{{--
|--------------------------------------------------------------------------
| Event
|--------------------------------------------------------------------------
|
--}}
<td>
	@if($model->event)
		@include("manage.frame.widgets.grid-text" , [
			'text' => $model->event->title,
		]     )
	@else
		-
	@endif

	@include("manage.frame.widgets.grid-date" , [
		'date' => $model->created_at,
	]     )
</td>


{{--
|--------------------------------------------------------------------------
| City
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => ($city_name = $user->home_city_name) ? $city_name : '-',
	]     )
</td>


{{--
|--------------------------------------------------------------------------
| Domain]
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => ($domain_name = $user->from_domain_name) ? $domain_name : '-',
	]     )
</td>

