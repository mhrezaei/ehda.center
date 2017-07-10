
@if(sizeof($topbar_notification_menu = Manage::topbarNotificationMenu() )>1)
	@include('manage.frame.widgets.topbar' , [
		'icon' => 'bell' ,
		'items' => $topbar_notification_menu ,
		'counter' => $topbar_notification_menu['total']  ,
		'color' => 'coral'
	])
@endif

@if(sizeof($topbar_create_menu = Manage::topbarCreateMenu() ))
	@include('manage.frame.widgets.topbar' , [
		'icon' => 'plus-circle' ,
		'items' => $topbar_create_menu ,
		'color' => 'green' ,
	])
@endif

@include('manage.frame.widgets.topbar' , [
	'icon' => 'user' ,
	'color' => 'grey' ,
	'text' => Auth::user()->full_name ,
	'items' => [
		['manage/account' , trans('settings.account') , 'sliders'] ,
		['-'] ,
		['/logout' , trans('manage.logout') , 'sign-out'] ,
	]
])
