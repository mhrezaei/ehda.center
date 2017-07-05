@include('manage.frame.widgets.sidebar-link' , [
	'icon' => 'dashboard' ,
	'caption' => trans('manage.dashboard') ,
	'link' => 'index' ,
])

{{--
|--------------------------------------------------------------------------
| Manual Menus
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.sidebar-link" , [
	'fake' => $card_holder_role = model('role')::findBySlug('card-holder') ,
	'caption' => $card_holder_role->title,
	'permission' => "users-card-holder" ,
	'link' => "" ,
	'icon' => $card_holder_role->spreadMeta()->icon ,
	'sub_menus' => [
		['cards/create' , trans("ehda.cards.create") , 'plus-circle'],
		['cards/browse/all' , trans('ehda.cards.browse') , 'bars'],
		['cards/printings' , trans("ehda.printings.title") , 'print'],
	] ,
]     )


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
| Comments
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.sidebar-link" , [
	'caption' => trans('posts.comments.users_comments') ,
	'link' => "comments" , 
	'permission' => "comments",
	'icon' => "comment",
])



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
	'link' => "jafarz",
	'sub_menus' => Manage::sidebarSettingsMenu() ,
	'caption' => trans('settings.site_settings'),
])
