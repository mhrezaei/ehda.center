@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	@include("manage.frame.widgets.toolbar" , [
		'title' => $page[1][1].' / '.$page[2][1],
		'buttons' => [
			[
				'condition' => isset($province),
				'target' => "modal:manage/upstream/edit/city/0/".(isset($province)?$province->id:''),
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
		'row_view' => "manage.settings.states-cities-row",
		'handle' => "counter",
		'headings' => [
			trans('settings.city'),
			trans('validation.attributes.province_id'),
			'',
		],
	])

@endsection