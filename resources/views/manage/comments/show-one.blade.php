<div class="mv10 p10 panel panel-{{ $model->id == $comment->id ? 'primary bg-light' : 'default' }}" style="{{ $parent? '' : "margin-right:50px" }}">
	@if(!$comment->type)
		<div class="noContent">{{ trans('forms.status_text.deleted') }}</div>
	@else
		<div class="{{ $comment->trashed()? 'noContent' : '' }}">
			@include("manage.comments.show-sender")
			@include("manage.comments.show-post")
			@include("manage.comments.show-one-content")
			@include("manage.frame.widgets.grid-date" , [
				'date' => $comment->created_at,
			])

			<div style="text-align: left">
				@if($comment->can('edit'))
					<a href="#" class="f10" onclick="masterModal(url('manage/comments/act/{{$comment->id}}/edit'))">{{ trans('forms.button.edit') }}</a>
				@endif
			</div>

		</div>
	@endif
</div>

