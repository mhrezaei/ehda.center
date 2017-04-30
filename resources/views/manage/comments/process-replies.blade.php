{{ '' , $children_count = $model->children()->count() }}

{{--
|--------------------------------------------------------------------------
| Browse Current Replies
|--------------------------------------------------------------------------
|
--}}
@if($children_count)
	@foreach($model->children()->orderBy('created_at' , 'desc')->get() as $comment)
		@include("manage.comments.process-showOne")
	@endforeach
@endif


{{--
|--------------------------------------------------------------------------
| Status and New Reply
|--------------------------------------------------------------------------
|
--}}
<div id="divCommentNewReply-handle"  class="margin-auto text-center mv20 {{ !$children_count? 'noDisplay' : '' }}">
	<span class="btn btn-default" onclick="$('#divCommentNewReply,#divCommentNewReply-handle').toggle();$('#txtReply').focus()">
		{{ trans('posts.comments.reply_or_change_status') }}
	</span>
</div>
<div id="divCommentNewReply" class=" {{ $children_count? 'noDisplay' : '' }}">
	@include("manage.comments.process-newReply")
</div>
