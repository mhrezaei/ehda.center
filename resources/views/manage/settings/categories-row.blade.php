@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/categories/update/$model->id"
])

<td>
	@include("manage.frame.widgets.grid-text" , [
		'text' => $model->title,
		'condition' => $model->title,
		'link' => "modal:manage/categories/edit/folder/-id-",
	])
	@include("manage.frame.widgets.grid-text" , [
		'text' => trans("posts.categories.no_folder"),
		'color' => "gray",
		'class' => 'null-content',
		'condition' => !$model->title,
	])
</td>

<td>
	@foreach($model->categories as $category)
		@include("manage.frame.widgets.grid-badge" , [
			'text' => $category->title,
			'link' => "modal:manage/categories/edit/".$category->id,
			'opacity' => "1.0",
			'color' => "info",
			'icon' => "check",
		])
	@endforeach
	@include("manage.frame.widgets.grid-badge" , [
		'text' => trans("posts.categories.new_category"),
		'link' => "modal:manage/categories/create/-id-",
		'opacity' => "1.0",
		'color' => "default",
		'icon' => "plus-circle",
	])
</td>