@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/settings/act/$model->id/packs-row" //@TODO
])

{{--
|--------------------------------------------------------------------------
| Posttype Title
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
	])
</td>

{{--
|--------------------------------------------------------------------------
| Actives
|--------------------------------------------------------------------------
|
--}}

<td>
	@foreach($model->packs()->get() as $pack)
		@include("manage.frame.widgets.grid-badge" , [
			'text' => $pack->title,
			'link' => "modal:manage/settings/act/$pack->id/edit-pack" ,
			'color' => "success" ,
			'icon' => "check" ,
		]     )
	@endforeach

	@include("manage.frame.widgets.grid-badge" , [
		'text' => trans('posts.packs.add'),
		'link' => "modal:manage/settings/act/-id-/create-pack" ,
		'color' => "default" ,
		'icon' => "plus-circle" ,
	]     )
</td>

{{--
|--------------------------------------------------------------------------
| Inactives
|--------------------------------------------------------------------------
|
--}}

<td>
	@foreach($model->packs()->onlyTrashed()->get() as $pack)
		@include("manage.frame.widgets.grid-badge" , [
			'text' => $pack->title,
			'link' => "modal:manage/settings/act/$pack->id/edit-pack" ,
			'color' => "warning" ,
			'icon' => "times" ,
		]     )
	@endforeach
</td>