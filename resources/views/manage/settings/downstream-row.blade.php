@include('manage.frame.widgets.grid-rowHeader')

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => "modal:manage/upstream/edit/downstream/-id-",
	])
	@include("manage.frame.widgets.grid-badges" , [
		'badges' => [
			[
				'text' => $model->slug,
				'condition' => true,
				'icon' => "code",
				'color' => "default",
			],
		]
	])
</td>

<td>
{{ trans('forms.data_type.'.$model->data_type) }}
</td>

<td>
{{ trans('settings.categories.'.$model->category) }}
@include("manage.frame.widgets.grid-badges" , [
	'badges' => [
		[
			'text' => trans('settings.is_resident'),
			'condition' => $model->is_resident,
			'icon' => "bookmark",
			'color' => "success",
		],
		[
			'text' => trans('settings.developers_only'),
			'condition' => $model->developers_only,
			'icon' => "github-alt",
			'color' => "warning",
		],
		[
			'text' => trans('settings.is_localized'),
			'condition' => $model->is_localized,
			'icon' => "globe",
			'color' => "info",
		],
	],
])
</td>

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('forms.button.set'),
		'link' => "modal:manage/upstream/downstream/-id-",
		'class' => "btn btn-default",
		'icon' => "eye",
	])
	{{--<a href="javascript:void(0)" onclick="masterModal('{{url("manage/upstream/downstream/$model->id")}}')">--}}
	{{--@if($model->value())--}}
	{{--@if(in_array($model->data_type , ['text' , 'textarea' , 'array']))--}}
	{{--{{ str_limit($model->value() , 50) }}--}}
	{{--@elseif($model->data_type == 'boolean')--}}
	{{--<i class="fa fa-check"></i>--}}
	{{--@elseif($model->data_type == 'date')--}}
	{{--@pd(jdate($model->value())->format('Y/m/d'))--}}
	{{--@elseif($model->data_type == 'photo')--}}
	{{--<i class="fa fa-image"></i>--}}
	{{--@endif--}}
	{{--@else--}}
	{{--<i class="text-grey">{{ trans('manage.settings.downstream_settings.unset') }}</i>--}}
	{{--@endif--}}
	{{--</a>--}}
</td>