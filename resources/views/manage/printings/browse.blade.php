@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.printings.tabs')
	</div>
	
	@include("manage.frame.widgets.toolbar" , [
		'buttons' => [
			[
				'target' => "modal:manage/cards/printings/0/revert-to-pending" ,
				'type' => "danger" ,
				'condition' => true ,
				'icon' => "undo" ,
				'caption' => trans("ehda.printings.revert_to_pending") ,
			],
			[
				'target' => "modal:manage/cards/printings/0/confirm-quality" ,
				'type' => "success" ,
				'condition' => true ,
				'icon' => "check" ,
				'caption' => trans("ehda.printings.verify_quality") ,
			],
			[
				'target' => "modal:manage/cards/printings/0/add-to-excel" ,
				'type' => "primary" ,
				'condition' => user()->as('admin')->can('users-card-holder.print-excel') ,
				'icon' => "file-excel-o" ,
				'caption' => trans("manage.permissions.print-excel") ,
			],
			[
				'target' => "modal:manage/cards/printings/0/add-to-direct" ,
				'type' => "primary" ,
				'condition' => user()->as('admin')->can('users-card-holder.print-direct') ,
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


