@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.posts.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
//		'title' => $page[0][1].' / '.$page[1][1].' / '.$page[2][1],
		'mass_actions' => [
			['trash-o' , trans('forms.button.soft_delete') , "modal:manage/posts/act/-id-/delete", $page[1][0]!='bin'],
			['recycle' , trans('forms.button.undelete') , "modal:manage/posts/act/-id-/undelete" , $page[1][0]=='bin'],
			['times' , trans('forms.button.hard_delete') , "modal:manage/posts/act/-id-/destroy" , $page[1][0]=='bin'],
		] ,

		'buttons' => [
			[
				'target' => url("manage/posts/$posttype->slug/create/$locale"),
				'type' => "success",
				'caption' => trans('forms.button.add_to').' '.$posttype->title ,
				'icon' => "plus-circle",
				'condition' => $posttype->can('create') ,
			],
			[
				'target' => "modal:manage/settings/act/$posttype->id/posttype" ,
				'type' => "primary" ,
				'caption' => trans('settings.downstream') ,
				'icon' => "gear" ,
				'condition' => user()->as('admin')->can('super') ,
			]
		],
		'search' => [
			'target' => url("manage/posts/$posttype->slug/$locale/search"),
			'label' => trans('forms.button.search'),
			'value' => isset($keyword)? $keyword : '' , trans('validation.attributes.education')
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblPosts",
		'row_view' => "manage.posts.browse-row",
		'handle' => "selector",
		'headings' => [
			[trans('validation.attributes.featured_image') , '200' , $posttype->hasFeature('featured_image')],
			trans('validation.attributes.properties'),
			[trans('validation.attributes.price'),'', $posttype->has('price') ],
			[trans('posts.features.feedback') , '100' , $posttype->has('feedback') ],
			trans('forms.button.action')
		],
	])

@endsection


