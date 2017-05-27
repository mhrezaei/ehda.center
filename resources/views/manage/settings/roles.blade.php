@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/upstream/edit/role/0",
				'type' => "success",
				'caption' => trans('forms.button.add'),
				'icon' => "plus-circle",
			],
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblRoles",
		'row_view' => "manage.settings.roles-row",
		'handle' => "counter",
		'headings' => [
			trans('validation.attributes.title'),
			trans('people.people'),
			trans('validation.attributes.status'),
			trans('forms.button.action')
		],
	])

@endsection