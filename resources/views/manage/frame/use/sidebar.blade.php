@include('manage.frame.widgets.sidebar-link' , [
	'icon' => 'dashboard' ,
	'caption' => trans('manage.dashboard') ,
	'link' => 'index' ,
])

{{--
|--------------------------------------------------------------------------
| Automatic Posts Menu
|--------------------------------------------------------------------------
|
--}}

@foreach(Manage::sidebarPostsMenu() as $item)
	@include("manage.frame.widgets.sidebar-link" , $item)
@endforeach

{{--
|--------------------------------------------------------------------------
| Automatic Users Menu
|--------------------------------------------------------------------------
|
--}}

@foreach(Manage::sidebarUsersMenu() as $item)
	@include("manage.frame.widgets.sidebar-link" , $item)
@endforeach

{{--
|--------------------------------------------------------------------------
| Folded Settings
|--------------------------------------------------------------------------
|
--}}

@include("manage.frame.widgets.sidebar-link" , [
	'icon' => "cogs",
	'link' => "asd",
	'sub_menus' => [
		['account' , trans('settings.account') , 'sliders'],
		['settings' , trans('settings.downstream') , 'cog' , user()->isSuper()],
		['categories' , trans('posts.categories.meaning') , 'folder-o' , user()->isSuper()],
		['upstream' , trans('settings.upstream') , 'github-alt' , user()->isDeveloper()],
	],
	'caption' => trans('settings.site_settings'),
])