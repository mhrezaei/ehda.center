@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/posts/update/$model->id"
])

@if(isset($posttype) and $posttype->slug == $model->type)
	{{ '' , $model->setPosttype($posttype) }}
@endif


{{--
|--------------------------------------------------------------------------
| Featured_image
|--------------------------------------------------------------------------
| available for the posttypes with the 'featured_image' feature
--}}
@if($model->has('featured_image'))
	<td style="vertical-align: middle;text-align: center">
		<div class="featured_image">
			<img class="featured_image-" src="{{$model->image}}">
		</div>
	</td>
@endif

{{--
|--------------------------------------------------------------------------
| Properties
|--------------------------------------------------------------------------
| Title, Status, Locale, Important Dates
--}}
@if($model->isCopy())
	{{ '' , $model->title = '['.trans('posts.form.copy').'] '.$model->title }}
@endif

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'link' => $model->canEdit()? url("manage/posts/$model->type/edit/$model->hash_id") : '',
		'size' => "14" ,
//		'link' => $model->canEdit()? "modal:manage/posts/act/-id-/quick_edit" : '',
	])
	
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans('forms.button.duration' , [
			'date1' => echoDate($model->starts_at , 'j F Y'),
			'date2' => echoDate($model->ends_at , 'j F Y'),
		]),
		'condition' => $model->has('event'),
		'color' => "info",
		'size' => "10",
		'icon' => "clock-o",
	])

	@include("manage.frame.widgets.grid-badges" , [$badges = [
		[
			'text' => trans('posts.form.copy'),
			'color' => "orange",
			'icon' => "pencil",
			'link' => "urlN:".$model->parent->browse_link ,
			'condition' => $model->isCopy(),
		],
		[
			'text' => trans("forms.status_text.$model->status"),
			'color' => trans("forms.status_color.$model->status"),
			'icon' => trans("forms.status_icon.$model->status"),
		],
		[
			'text' => trans('posts.form.rejected'),
			'color' => "danger",
			'icon' => "undo",
			'condition' => $model->isRejected(),
		],
		[
			'text' => trans('posts.form.is_not_available'),
			'color' => "danger",
			'icon' => "exclamation-triangle",
			'condition' => !$model->trashed() and $model->has('price') and !$model->is_available,
		],
		[
			'condition' => $domain_name = $model->domain_name ,
			'text' => $domain_name ,
			'icon' => "code-fork" ,
			'color' => "info" ,
		],
		[
			'text' => trans("forms.lang.$model->locale"),
			'condition' => $model->has('locales'),
			'link' => "modal:manage/posts/act/-id-/locales/" ,
			'icon' => "globe",
		]
	]])

	@include("manage.frame.widgets.grid-date" , [
		'text' => trans('forms.general.created_at').': ',
		'text2' => trans('forms.general.by').' '.$model->creator->full_name,
		'date' => $model->created_at,
	])

	@include("manage.frame.widgets.grid-tiny" , [
		'text' => trans('posts.form.post_owner').': '.$model->getPerson('owned_by')->full_name,
		'link' => "modal:manage/posts/act/-id-/owner" ,
		'icon' => "user-o" ,
	]     )

	@include("manage.frame.widgets.grid-date" , [
		'text' => trans('posts.form.publish').': ',
		'text2' => trans('forms.general.by').' '.$model->publisher->full_name,
		'date' => $model->published_at,
		'condition' => $model->isPublished(),
	])

	@include("manage.frame.widgets.grid-date" , [
		'text' => trans('forms.general.deleted_at').': ',
		'text2' => trans('forms.general.by').' '.$model->deleter->full_name,
		'date' => $model->deleted_at,
		'condition' => $model->trashed(),
		'color' => "danger",
	])
	

</td>


{{--
|--------------------------------------------------------------------------
| Price
|--------------------------------------------------------------------------
| Main Price ,Sell Price, sale expire, availabilty
--}}

@if($model->has('price'))
	<td>
		@include("manage.frame.widgets.grid-text" , [
			'condition' => $model->price >0,
			'text' => number_format(intval($model->price)).' '.setting()->ask('currency')->in($model->locale)->gain(),
		])

		@include("manage.frame.widgets.grid-tiny" , [
			'condition' => $model->sale_price > 0,
			'text' => trans('validation.attributes.sale_price').': '. number_format(intval($model->sale_price)). ' ' . setting()->in('model->locale')->gain('currency'),
			'color' => "teal",
			'icon' => "bolt",
		])
		@include("manage.frame.widgets.grid-tiny" , [
			'text' => trans('posts.form.discount_percent_in_parentheses' , ['percent' => $model->discount_percent,]),
			'condition' => $model->sale_price > 0,
			'color' => "teal",
			'icon' => "",
		])
		@include("manage.frame.widgets.grid-tiny" , [
			'text' => trans('forms.general.till').' '.jDate::forge($model->sale_expires_at)->format('j F Y [H:i]'),
			'color' => "teal",
			'icon' => "",
			'condition' => $model->sale_expires_at != '',
		])
	</td>
@endif


{{--
|--------------------------------------------------------------------------
| Feedback
|--------------------------------------------------------------------------
|
--}}
@if($model->has('feedback'))
	<td>
		@include("manage.frame.widgets.grid-text" , [
			'condition' => $model->has('event') and $model->total_receipts_count,
			'text' => trans('cart.receipts_count_amount' , [
				'count' => number_format($model->total_receipts_count),
				'amount' => number_format($model->total_receipts_amount/10),
			])
		])

		@include("manage.frame.widgets.grid-text" , [
			'condition' => $model->has('event') and \Carbon\Carbon::now() > $model->ends_at and !count($model->winners_array),
			'text' => trans('cart.draw'),
			'icon' => "gift",
			'link' => "modal:manage/posts/act/-id-/draw",
			'class' => "btn btn-default btn-lg",
		])

		@include("manage.frame.widgets.grid-tiny" , [
			'condition' => $total_winners = count($model->winners_array),
			'text' => pd($total_winners).' '.trans('cart.drawing_winner'),
			'icon' => "smile-o",
			'color' => "primary",
			'link' => "modal:manage/posts/act/-id-/draw-winners" ,
		])
		
		@include("manage.frame.widgets.grid-tiny" , [
			'condition' => $model->has('comment'),
			'text' => pd($model->total_comments+0) . ' ' . trans('posts.comments.singular') ,
			'icon' => "comments-o" ,
			'link' => "url:manage/comments/all/post_id=-id-" ,
		]     )

@endif

{{--
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
| Action Buttons
--}}
@include("manage.frame.widgets.grid-actionCol" , [ "actions" => [
	['eye' , trans('posts.form.view_in_site') , "urlN:$model->site_link" , $model->isPublished() and $model->has('preview')],
	['eye-slash', trans('posts.form.preview') , "urlN:$model->preview_link" , $model->has('preview')],
	['-' , $model->has('preview')],

	['pencil' , trans('forms.button.edit') , "url:manage/posts/$model->type/edit/-hash_id-" , $model->canEdit()],
//	['pencil-square-o' , trans('posts.form.quick_edit'), "modal:manage/posts/act/-id-/quick_edit" , $model->canEdit()],
	['user-o' , trans('posts.form.post_owner') , "modal:manage/posts/act/-id-/owner/1" , $model->canPublish()],
	['clone' , trans('posts.form.clone') , "modal:manage/posts/act/-id-/clone" , $model->can('create')],
	['globe' , trans('posts.features.locales') , "modal:manage/posts/act/-id-/locales/" , $model->has('locales')],
	['-' , $model->can('create') or $model->canEdit()],

	['trash-o' , trans('forms.button.soft_delete') , "modal:manage/posts/act/-id-/delete" , $model->canDelete() and !$model->trashed()] ,
	['recycle' , trans('forms.button.undelete') , "modal:manage/posts/act/-id-/undelete" , $model->canDelete() and $model->trashed()],
	['times' , trans('forms.button.hard_delete') , "modal:manage/posts/act/-id-/destroy" , $model->canDelete() and $model->trashed()],
]])
