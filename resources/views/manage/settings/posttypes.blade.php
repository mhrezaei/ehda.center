@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/upstream/edit/posttype",
				'type' => "success",
				'caption' => trans('forms.button.add'),
				'icon' => "plus-circle",
			],
		],
		'search' => [
			'target' => url('manage/upstream/posttypes/search/') ,
			'label' => trans('forms.button.search') ,
			'value' => isset($key)? $key : '' ,
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblPosttypes",
		'row_view' => "manage.settings.posttypes-row",
		'handle' => "counter",
		'headings' => [
			[trans('validation.attributes.title'),150],
			trans('posts.features.meaning'),
			trans('posts.categories.meaning')
		],
	])

@endsection