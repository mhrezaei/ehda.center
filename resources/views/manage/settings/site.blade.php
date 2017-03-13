@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs')
	@include("manage.frame.widgets.toolbar" , [
		'search' => [
			'target' => url("manage/settings/search/"),
			'label' => trans('forms.button.search'),
			'value' => isset($keyword)? $keyword : '' ,
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblSettings",
		'row_view' => "manage.settings.browse-row",
		'handle' => "counter",
		'headings' => [
			trans('validation.attributes.title'),
			[trans('forms.button.action'),200],
		],
	])

@endsection