@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/orders/update/$model->id"
])

{{--
|--------------------------------------------------------------------------
| Properties
|--------------------------------------------------------------------------
| doesn't support 'subject' & ip so far!
--}}

<td>
	@include('manage.orders.show-client')
	@include('manage.orders.show-posts')

	@include("manage.frame.widgets.grid-date" , [
		'date' => $model->created_at,
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
| Invoice Amount
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->invoice_amount,
	])
</td>

{{--
|--------------------------------------------------------------------------
| Payable Amount
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->payable_amount,
	])
</td>

{{--
|--------------------------------------------------------------------------
| Paid Amount
|--------------------------------------------------------------------------
|
--}}
<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->paid_amount,
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
		'text' => trans("forms.status_text.$model->status_name") ,
		'color' => trans("forms.status_color.$model->status_name") ,
//		'link' => $model->trashed()? '' : "modal:manage/comments/act/-id-/show",
		'icon' => trans("forms.status_icon.$model->status_name") ,
	])
</td>

{{--
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.grid-actionCol" , [ "actions" => [
	['pencil' , trans('forms.button.edit') , "modal:manage/orders/act/-id-/edit" , !$model->trashed() and $model->can('edit')],
//	['eye' , trans('forms.button.show_details') , 'modal:manage/comments/act/-id-/show' , !$model->trashed()],
//	['trash-o' , trans('forms.button.soft_delete') , "modal:manage/orders/act/-id-/delete" , $model->can('delete') and !$model->trashed()] ,
//	['recycle' , trans('forms.button.undelete') , "modal:manage/comments/act/-id-/undelete" , $model->can('bin') and $model->trashed()],
//	['times' , trans('forms.button.hard_delete') , "modal:manage/comments/act/-id-/destroy" , $model->can('bin') and $model->trashed()],
]])