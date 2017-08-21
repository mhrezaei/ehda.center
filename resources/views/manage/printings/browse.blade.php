@extends('manage.frame.use.0')

@section('section')
	<input id="txtEventId" type="hidden" value="{{$event_id}}">

	<div id="divTab">
		@include('manage.printings.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
		'free_toolbar_view' =>  "manage.printings.browse-free-toolbar",
		'subtitle_view' => "manage.printings.browse-subtitle" ,
		'buttons' => [
			[
				'target' => "modal:manage/cards/printings/act/revert-to-pending" ,
				'type' => "danger" ,
				'condition' => ($request_tab != 'pending') ,
				'icon' => "undo" ,
				'caption' => trans("ehda.printings.revert_to_pending") ,
			],
			[
				'target' => "modal:manage/cards/printings/act/confirm-quality" ,
				'type' => "success" ,
				'condition' => ($request_tab != 'pending') ,
				'icon' => "check" ,
				'caption' => trans("ehda.printings.verify_quality") ,
			],
			[
				'target' => "modal:manage/cards/printings/act/add-to-excel" ,
				'type' => "primary" ,
				'condition' => ($request_tab == 'pending' and user()->as('admin')->can('users-card-holder.print-excel')) ,
				'icon' => "file-excel-o" ,
				'caption' => trans("manage.permissions.print-excel") ,
			],
			[
				'target' => "modal:manage/cards/printings/act/add-to-direct" ,
				'type' => "primary" ,
				'condition' => ($request_tab == 'pending' and user()->as('admin')->can('users-card-holder.print-direct')) ,
				'icon' => "print" ,
				'caption' => trans("manage.permissions.print-direct") ,
			],

		] ,
//		'search_' =>[
//			'target' => url("manage/".$switches['url']."/search")  ,
//			'label' => trans('forms.button.search') ,
//			'value' => isset($keyword)? $keyword : '' ,
//		]
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblPrintings",
		'row_view' => "manage.printings.browse-row" ,
		'handle' => "selector",
		'headings' => [
			trans('validation.attributes.name_first') ,
			trans('validation.attributes.from_event_id'),
			trans('validation.attributes.home_city'),
			trans('validation.attributes.domain'),
		],
	])

@endsection


