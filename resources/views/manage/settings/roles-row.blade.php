@include('manage.frame.widgets.grid-rowHeader')

{{--
|--------------------------------------------------------------------------
| Title
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/role/-id-",
		'icon' => $model->icon,
	])
</td>

{{--
|--------------------------------------------------------------------------
| People Count
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'condition' => $count = $model->users()->count() ,
		'text' => number_format($count) .' '.trans('people.person'),
		'link' => $model->trashed() ? '' : "url:$model->users_browse_link" ,
	]     )
	@include("manage.frame.widgets.grid-text" , [
		'condition' => !$count,
		'text' => trans('forms.general.nobody') ,
		'link' => $model->trashed() ? '' : "url:$model->users_browse_link" ,
	]     )
</td>

{{--
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
|
--}}

<td>
	{{--@if($model->isDefault())--}}
		{{--@include("manage.frame.widgets.grid-badge" , [--}}
			{{--'icon' => "check-square-o",--}}
			{{--'text' => trans('people.default_role') ,--}}
			{{--'color' => "primary" ,--}}
			{{--'class' => "text-white" ,--}}
		{{--]     )--}}
	{{--@else--}}
		@include("manage.frame.widgets.grid-badge" , [
			'icon' => trans("forms.status_icon.$model->status"),
			'text' => trans("forms.status_text.$model->status"),
			'link' => 'modal:manage/upstream/edit/role-activeness/-id-' ,
			'color' => trans("forms.status_color.$model->status"),
		])
	{{--@endif--}}

	@if($model->is_admin)
		@include("manage.frame.widgets.grid-badge" , [
			'icon' => "user-secret",
			'text' => trans('people.is_admin') ,
			'color' => "warning" ,
		]     )
	@endif
</td>

{{--
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
|
--}}

@include("manage.frame.widgets.grid-actionCol" , [
	'refresh_action' => false ,
	"actions" => [
		['pencil' , trans('forms.button.edit') , "modal:manage/upstream/edit/role/-id-" ],
		['taxi' , trans('posts.types.locale_titles') , 'modal:manage/upstream/edit/role-titles/-id-' ],
//		['hand-pointer-o' , trans('people.choose_as_default_role') , 'modal:manage/upstream/edit/role-default/-id-'],
		['trash-o' , trans('forms.button.soft_delete') , 'modal:manage/upstream/edit/role-activeness/-id-' , !$model->trashed() and !$model->isDefault()  , $model::adminRoles()] ,
		['recycle' , trans('forms.button.undelete') , 'modal:manage/upstream/edit/role-activeness/-id-' , $model->trashed()],
	]
])
