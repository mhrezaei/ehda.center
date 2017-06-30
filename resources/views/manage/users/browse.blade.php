@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.users.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
		'buttons_' => [
			[
				'target' => "modal:manage/users/create/$request_role",
				'type' => $request_role=='all' ? 'primary' : 'success' ,
				'caption' => trans('people.commands.create_new_user' , ['role_title' => $request_role=='all' ? trans('people.user') : $role->title,]) ,
				'icon' => 'plus-circle' ,
			],
		],
		'search' =>[
			'target' => url("manage/".$switches['url']."/search")  ,
			'label' => trans('forms.button.search') ,
			'value' => isset($keyword)? $keyword : '' ,
		]
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblUsers",
		'row_view' => "manage.users.".$switches['grid_row'] ,
		'handle' => "selector",
		'headings' => $switches['grid_array'],
	])

@endsection


