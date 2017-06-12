@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/upstream/edit/domain/0",
				'type' => "success",
				'caption' => trans('forms.button.add'),
				'icon' => "plus-circle",
			],
		],
		'search' => [
			'target' => url('manage/upstream/domains/search/') ,
			'label' => trans('forms.button.search') ,
			'value' => isset($key)? $key : '' ,
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblDomains",
		'row_view' => "manage.settings.domains-row",
		'handle' => "counter",
		'headings' => [
			trans('validation.attributes.title'),
			trans('validation.attributes.slug'),
			trans('validation.attributes.alias'),
			trans('validation.attributes.cities')
		],
	])

@endsection