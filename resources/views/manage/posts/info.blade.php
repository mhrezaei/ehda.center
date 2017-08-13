@include("templates.modal.start" , [
	'modal_title' => trans("posts.form.info"),
]     )

<div class="modal-body profile">


	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	| Titles and badges
	--}}
	<div class="w90">
		@include("manage.frame.widgets.grid-text" , [
			'text' => $model->title,
			'icon' => $model->posttype->icon ,
			'size' => "30" ,
		]     )


		@include("manage.frame.widgets.grid-text" , [
			'condition' => $model->has('title2') and $model->title2 ,
			'text' => $model->title2 ,
			'size' => "20" ,
		]      ) 

		@include("manage.posts.badges")
	</div>


	{{--
	|--------------------------------------------------------------------------
	| People and Dates
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="w90">
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
	</div>

	{{--
	|--------------------------------------------------------------------------
	| Buttons
	|--------------------------------------------------------------------------
	|
	--}}

	<div class="modal-footer">

		@if($model->canEdit())
			<a href="{{ url("manage/posts/$model->type/edit/$model->hash_id") }}" class="btn btn-default w30">
				{{ trans('forms.button.edit')   }}
			</a>
		@endif
		@if(!$model->isPublished() and $model->has('preview'))
			<a href="{{ $model->preview_link }}" target="_blank" class="btn btn-default w30">
				{{ trans('posts.form.preview') }}
			</a>
		@endif
		@if($model->isPublished() and $model->has('preview'))
			<a href="{{ $model->site_link }}" target="_blank" class="btn btn-default w30">
				{{ trans('posts.form.view_in_site') }}
			</a>
		@endif

	</div>
</div>




@include("templates.modal.end")