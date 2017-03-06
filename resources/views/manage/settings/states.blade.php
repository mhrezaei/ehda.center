@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/upstream/edit/state/0",
				'type' => "success",
				'caption' => trans('forms.button.add'),
				'icon' => "plus-circle",
			],
		],
		'search' => [
			'target' => url('manage/upstream/states/search/') ,
			'label' => trans('forms.button.search') ,
			'value' => isset($key)? $key : '' ,
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblStates",
		'row_view' => "manage.settings.states-row",
		'handle' => "counter",
		'headings' => [
			trans('validation.attributes.province_id'),
			trans('validation.attributes.capital_id'),
			trans('validation.attributes.cities'),
		],
	])

@endsection