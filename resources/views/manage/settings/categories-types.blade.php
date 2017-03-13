@include("manage.frame.widgets.toolbar" , [
	'title' => trans('posts.categories.category_enabled_content'),
])

@include("manage.frame.widgets.grid" , [
	'fake' => $models = $posttypes,
	'table_id' => "tblFolders",
	'row_view' => "manage.settings.categories-types-row",
	'handle' => "counter",
	'headings' => [
		trans('validation.attributes.title'),
		trans('posts.categories.meaning'),
	],
])
