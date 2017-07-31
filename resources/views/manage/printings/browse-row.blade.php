@include('manage.frame.widgets.grid-rowHeader' )

{{--
|--------------------------------------------------------------------------
| Safe Delete
|--------------------------------------------------------------------------
|
--}}
{{ '' , $user = $model->user }}
@if(!$user or !$user->id)
	{{ null , $model->delete() }}
@endif

{{--
|--------------------------------------------------------------------------
| Name
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $user->full_name ,
		'link' => ($user->id and user()->as('admin')->can('users-card-holder.view'))? "modal:manage/cards/view/$user->hash_id" : '',
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans('validation.attributes.card_no').': '.$user->card_no,
		'color' => "gray" ,
		'icon' => "credit-card",
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans("validation.attributes.id").': '.$model->id,
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