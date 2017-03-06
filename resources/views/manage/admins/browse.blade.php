@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.admins.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/admins/create",
				'type' => 'success' ,
				'caption' => trans('people.admins.create') ,
				'icon' => 'plus-circle' ,
			],
		],
		'search' =>[
			'target' => url('manage/admins/search/') ,
			'label' => trans('forms.button.search') ,
			'value' => isset($keyword)? $keyword : '' ,
		]
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblAdmins",
		'row_view' => "manage.admins.browse-row",
		'handle' => "counter",
		'headings' => [
			trans('validation.attributes.name_first') ,
			trans('validation.attributes.position'),
			trans('validation.attributes.status'),
			trans('forms.button.action'),
		],
	])

@endsection


