{{--
|--------------------------------------------------------------------------
| Single Row of Sender Name
|--------------------------------------------------------------------------
| This is to be called wherever a single-line sender name (including all the links and hints) is required: browse-row, show-one, edit
--}}
{{ '' , isset($comment)? $row = $comment : $row = $model }}
{{ '' , $post = $row->post }}

@if($post)
	@include("manage.frame.widgets.grid-text" , [
		'text' => str_limit($post->posttype->title . ' / ' . $post->title , 100),
		'link' => "urlN:manage/posts/$post->type/all/id:$row->id" ,
		'icon' => $post->posttype->spreadMeta()->icon ,
	]     )
@else
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('posts.form.deleted_post') ,
	]     )
@endif
{{--		'link' => $row->user? "urlN:manage/users/browse/all/search?id=".$row->user_id."&searched=1" : null,
--}}

