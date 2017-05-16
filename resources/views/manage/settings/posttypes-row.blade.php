@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/posttype/-id-",
	])
	@include("manage.frame.widgets.grid-tiny" , [
		'icon' => $model->spreadMeta()->icon,
		'text' => $model->singular_title,
	])
</td>

<td>
	@foreach(json_decode($model->available_features) as $key => $feature)
		@if(in_array($key , $model->features_array))
			@include("manage.frame.widgets.grid-badge" , [
				'color' => $feature[1],
				'icon' => $feature[0],
				'text' => trans("posts.features.$key"),
				'opacity' => "0.7",
			])
		@endif
	@endforeach
</td>

@include("manage.frame.widgets.grid-actionCol" , [
	'refresh_action' => false ,
	"actions" => [
		['pencil' , trans('forms.button.edit') , "modal:manage/upstream/edit/posttype/-id-" ],
		['cog' , trans('settings.downstream') , "modal:manage/settings/act/-id-/posttype"],
		['taxi' , trans('posts.types.locale_titles') , 'modal:manage/upstream/edit/posttype-titles/-id-' ]
	]
])
