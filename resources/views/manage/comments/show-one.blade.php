<div class="mv10 p10 panel panel-{{ $model->id == $comment->id ? 'primary bg-light' : 'default' }}" style="{{ $parent? '' : "margin-right:50px" }}">
	@if(!$comment->type)
		<div class="noContent">{{ trans('forms.status_text.deleted') }}</div>
	@else
		<div class="{{ $comment->trashed()? 'noContent' : '' }}">
			@include("manage.frame.widgets.grid-text" , [
				'condition' => $comment->user_id,
				'icon' => $comment->is_by_admin? 'user-circle-o' : 'user' ,
				'color' => $comment->is_by_admin? 'orange' : '',
				'text' => ($comment->user? $comment->user->full_name : trans('people.deleted_user') ). ': ' ,
				'link' => $comment->user? "urlN:manage/users/browse/all/search?id=".$comment->user_id."&searched=1" : null,
			])
			@include("manage.frame.widgets.grid-text" , [
				'icon' => "user-o" ,
				'condition' => !$comment->user_id,
				'text' => "$comment->name ($comment->email): " ,
			])
			@include("manage.frame.widgets.grid-text" , [
				'text' => $comment->text ,
				'size' => "12" ,
				'class' => "text-align" ,
			])
			@include("manage.frame.widgets.grid-date" , [
				'date' => $comment->created_at,
			])
		</div>
	@endif
</div>

