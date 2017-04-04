@include("manage.frame.widgets.toolbar" , [
	'buttons' => [
		[
			'target' => "modal:manage/categories/create/folder/$type->id/$locale",
			'type' => "primary",
			'caption' => trans('posts.categories.new_folder'),
			'icon' => "plus-circle",
		],
	],
])

@include("manage.frame.widgets.grid" , [
	'fake' => $models = $type->folders()->where('locale' , $locale)->orderBy('title')->paginate(100),
	'table_id' => "tblFolders",
	'row_view' => "manage.settings.categories-row",
	'handle' => "counter",
	'headings' => [
		trans('posts.categories.folder'),
		trans('posts.categories.meaning'),
	],
])
