@php
	$model->spreadMeta();
	$relatedPost = $model->post->spreadMeta();
@endphp

{{--
|--------------------------------------------------------------------------
| Modal Title
|--------------------------------------------------------------------------
|
--}}
@include("templates.modal.start" , [
	'partial' => "true",
	'form_url' => $model->can('process')? url('manage/comments/save/process') : '' ,
	'modal_title' => trans('posts.comments.singular') ,
]     )

{{--
|--------------------------------------------------------------------------
| Parent
|--------------------------------------------------------------------------
|
--}}
@include("manage.comments.show-one" , [
	'comment' => $parent = $model->parent(),
	'parent' => true ,
]     )

{{--
|--------------------------------------------------------------------------
| Children
|--------------------------------------------------------------------------
|
--}}
@php $children = $parent->children()->orderBy('created_at')->get() @endphp
@foreach($children as $key => $child)
	@include("manage.comments.show-reply-one" , [
		'comment' => $child,
		'parent' => false ,
		'previewComment' => ($key == 0) ? null : $children[$key - 1]
	])
@endforeach

{{--
|--------------------------------------------------------------------------
| Reply
|--------------------------------------------------------------------------
|
--}}
@if($model->can('process') and $parent->type)
	<div class="mv10 p10 panel panel-default" style="margin-right: 50px">
		@include("manage.comments.show-reply")
	</div>
@endif
{{--
|--------------------------------------------------------------------------
| Modal Close
|--------------------------------------------------------------------------
|
--}}

@include("templates.modal.end")
