{{--
|--------------------------------------------------------------------------
| Single Row of Sender Name
|--------------------------------------------------------------------------
| This is to be called wherever a single-line sender name (including all the links and hints) is required: browse-row, show-one, edit
--}}
{{ '' , isset($comment)? $row = $comment : $row = $model }}

@if($row->id)

	@include("manage.frame.widgets.grid-text" , [
		'icon' => $row->is_by_admin ? 'user-circle-o' : 'user',
		'text' => $row->sender_name ,
		'link' => $row->user? "urlN:manage/users/browse/all/search?id=".$row->user_id."&searched=1" : null,
	]     )

@else

	@include("manage.frame.widgets.grid-text" , [
		'icon' => "user-o" ,
		'text' => "$row->name ($row->email): " ,
	])

@endif

