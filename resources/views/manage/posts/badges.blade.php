@include("manage.frame.widgets.grid-badges" , [$badges = [
	[
		'text' => trans("posts.pin.pinned") ,
		'color' => "purple text-white" ,
		'icon' => "thumb-tack" ,
		'link' => $model->canPublish() ? "modal:manage/posts/act/-id-/pin" : '',
		'condition' => $model->has('pin') and $model->pinned,
	],


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
