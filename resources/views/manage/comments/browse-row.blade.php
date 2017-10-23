@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/comments/update/$model->id"
])

{{--
|--------------------------------------------------------------------------
| Properties
|--------------------------------------------------------------------------
| doesn't support 'subject' & ip so far!
--}}

<td>
	@include("manage.comments.show-sender")
	@include("manage.comments.show-post")

 	@include("manage.frame.widgets.grid-text" , [
		'text' => str_limit($model->text,400),
		'text2' => $model->text ,
		'size' => "11" ,
		'class' => "text-align" ,
	])
	@include("manage.frame.widgets.grid-date" , [
		'date' => $model->created_at,
	])
	@include("manage.frame.widgets.grid-date" , [
		'text' => trans('posts.form.approval').': ' ,
		'date' => $model->published_at,
		'by' => $model->publisher->full_name ,
		'color' => "success" ,
		'condition' => $model->published_at != null ,
	])
	@include("manage.frame.widgets.grid-date" , [
		'text' => trans('forms.button.delete').': ' ,
		'date' => $model->deleted_at,
		'by' => $model->deleter->full_name ,
		'color' => "danger" ,
		'condition' => $model->trashed()  ,
	])
</td>

{{--
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("forms.status_text.$model->status") ,
		'color' => trans("forms.status_color.$model->status") ,
		'link' => $model->trashed() ? '' : "masterModal(url('" . route('manage.comments.show', ['model_hashid' => $model->hashid], false) . "'))",
		'icon' => trans("forms.status_icon.$model->status") ,
	])

	@include("manage.frame.widgets.grid-tiny" , [
		'icon' => $model->replied_on? "comments-o" : "comment-o",
		'text' => pd($model->parent()->children()->count()) . ' ' . trans('posts.comments.reply')  ,
		'link' => $model->trashed() ? '' : "modal:manage/comments/act/-id-/show",
//		'condition' => $model->published_at != null ,
	]   )
</td>

{{--
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.grid-actionCol" , [ "actions" => [
	['pencil' , trans('forms.button.edit') , "modal:manage/comments/act/-id-/edit" , !$model->trashed() and $model->can('edit')],
	['eye' , trans('forms.button.show_details') , 'modal:manage/comments/act/-id-/show' , !$model->trashed()],

	['trash-o' , trans('forms.button.soft_delete') , "modal:manage/comments/act/-id-/delete" , $model->can('delete') and !$model->trashed()] ,
	['recycle' , trans('forms.button.undelete') , "modal:manage/comments/act/-id-/undelete" , $model->can('bin') and $model->trashed()],
	['times' , trans('forms.button.hard_delete') , "modal:manage/comments/act/-id-/destroy" , $model->can('bin') and $model->trashed()],
]])
