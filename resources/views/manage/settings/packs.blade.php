@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs')
	@include("manage.frame.widgets.toolbar" )

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblPacks",
		'row_view' => "manage.settings.packs-row",
		'handle' => "counter",
		'headings' => [
			trans('settings.posttypes'),
			trans('manage.tabs.actives'),
			trans('manage.tabs.inactives')
		],
	])

@endsection