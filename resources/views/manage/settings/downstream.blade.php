@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/upstream/edit/downstream",
				'type' => "success",
				'caption' => trans('forms.button.add'),
				'icon' => "plus-circle",
			],
		],
		'search' => [
			'target' => url('manage/upstream/downstream/search/') ,
			'label' => trans('forms.button.search') ,
			'value' => isset($key)? $key : '' ,
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblDownstream",
		'row_view' => "manage.settings.downstream-row",
		'handle' => "counter",
		'headings' => [
			trans('validation.attributes.title'),
			trans('validation.attributes.data_type'),
			trans('validation.attributes.category_id'),
			trans('validation.attributes.value')
		],
	])

@endsection