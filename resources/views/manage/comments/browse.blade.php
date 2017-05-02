@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.comments.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
		'subtitle_view' => "manage.comments.browse-subtitle" ,
		'buttons-' => [
			[
				'target' => url("manage/posts/$posttype->slug/create/locale"),
				'type' => "success",
				'caption' => trans('forms.button.add_to').' '.$posttype->title ,
				'icon' => "plus-circle",
			],
		],
		'search-' => [
			'target' => url("manage/posts/$posttype->slug/locale/search"),
			'label' => trans('forms.button.search'),
			'value' => isset($keyword)? $keyword : '' , trans('validation.attributes.education')
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblComments",
		'row_view' => "manage.comments.browse-row",
		'handle' => "selector",
		'headings' => [
			trans('validation.attributes.properties'),
			[trans('validation.attributes.status'),150],
			trans('forms.button.action')
		],
	])

@endsection



