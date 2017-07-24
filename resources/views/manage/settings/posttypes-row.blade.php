@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/upstream/update-posttype/$model->id"
])

{{--
|--------------------------------------------------------------------------
| Icon
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'icon' => $model->spreadMeta()->icon ,
		'size' => "30" ,
		'text' => null,
		'link' => "modal:manage/upstream/edit/posttype/-id-",
	])
</td>


{{--
|--------------------------------------------------------------------------
| Title and some Properties
|--------------------------------------------------------------------------
|
--}}
<td>
	{{-- Title ----------------------------- --}}
	@include("manage.frame.widgets.grid-text" , [
		'size' => "14" ,
		'text' => $model->title,
		'div_class' => "mv5" ,
		'link' => "modal:manage/upstream/edit/posttype/-id-",
	])

	{{-- Languages ----------------------------- --}}
	{{ '' , $locales = ' ' }}
	<div class="text-gray">
		@foreach($model->locales_array as $locale)
			{{ '' , $locales .= trans("forms.lang.$locale") }}
			@if(!$loop->last)
				{{ '' , $locales .= trans("forms.general.comma") . ' ' }}
			@endif
		@endforeach
	</div>


	{{-- Singular Title ----------------------------- --}}
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("posts.form.for_each_number_of" , [	"name" => $model->singular_title ,]).$locales ,
		'color' => "gray" ,
	]     )


	{{-- Slug & ID ----------------------------- --}}
	<div class="text-gray mv5">
		[{{ $model->slug }}] [{{ $model->id }}]
	</div>

</td>

{{--
|--------------------------------------------------------------------------
| Operation
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'icon' => "pencil",
		'text' => trans("forms.button.edit") ,
		'class' => "btn btn-default btn-lg" ,
		'link' => "modal:manage/upstream/edit/posttype/-id-"  ,
	]     )
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'icon' => "cog",
		'text' => trans('settings.downstream') ,
		'class' => "btn btn-default btn-lg" ,
		'link' => "modal:manage/settings/act/-id-/posttype"  ,
	]     )
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'icon' => "taxi",
		'text' => trans('posts.types.locale_titles') ,
		'class' => "btn btn-default btn-lg" ,
		'link' => "modal:manage/upstream/edit/posttype-titles/-id-"  ,
	]     )
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'icon' => "retweet",
		'text' =>  trans('forms.button.refresh'),
		'class' => "btn btn-default btn-lg" ,
		'link' => "rowUpdate('auto','$model->id')"  ,
	]     )

</td>

	{{--@foreach(json_decode($model->available_features) as $key => $feature)--}}
		{{--@if(in_array($key , $model->features_array))--}}
			{{--@include("manage.frame.widgets.grid-badge" , [--}}
				{{--'color' => $feature[1],--}}
				{{--'icon' => $feature[0],--}}
				{{--'text' => trans("posts.features.$key"),--}}
				{{--'opacity' => "0.7",--}}
			{{--])--}}
		{{--@endif--}}
	{{--@endforeach--}}

