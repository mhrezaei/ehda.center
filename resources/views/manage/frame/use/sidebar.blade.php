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
	'link' => "cards" ,
	'icon' => 'credit-card' ,
	'sub_menus' => [
		['cards/create' , trans("ehda.cards.create") , 'plus-circle' , user()->as('admin')->can('card-holder.create') ],
		['cards/browse/all' , trans('ehda.cards.browse') , 'bars' , user()->as('admin')->can('card-holder.browse')],
		['cards/printings' , trans("ehda.printings.title") , 'print' , user()->as('admin')->can('card-holder.print')],
	] ,
]     )

@include("manage.frame.widgets.sidebar-link" , [
	'caption' => trans("ehda.volunteers.plural"),
	'permission' => "users-volunteer" ,
	'link' => "volunteers" ,
	'icon' => "child" ,
	'sub_menus' => [
		['volunteers/create' , trans("ehda.volunteers.create") , 'plus-circle' , count(user()->userRolesArray('create' , [] , model('role')::adminRoles()))],
		['volunteers/browse/all' , trans("ehda.volunteers.plural") , 'bars' , count(user()->userRolesArray('browse' , [] , model('role')::adminRoles()))],
	] ,
]     )

@include("manage.frame.widgets.sidebar-link" , [
	'caption' => trans("ehda.students.plural"),
	'permission' => "users-student" ,
	'link' => "students" ,
	'icon' => "graduation-cap" ,
	'sub_menus' => [
		['students' , trans("people.commands.all_users") , 'bars']
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
| Automatic Commenting Posts Menu
|--------------------------------------------------------------------------
|
--}}

@foreach(Manage::sidebarCommentingPostsMenu() as $item)
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
| Orders
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.sidebar-link" , [
	'caption' => trans('front.orders') ,
	'link' => "orders" ,
	'permission' => "orders",
	'icon' => "shopping-cart",
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
	'link' => "jafar",
	'sub_menus' => Manage::sidebarSettingsMenu() ,
	'caption' => trans('settings.site_settings'),
])
