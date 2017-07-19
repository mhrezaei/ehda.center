{{ '' , $topbar_notification_menu = Manage::topbarNotificationMenu() }}

@if($topbar_notification_menu['total'])
	@include('manage.frame.widgets.topbar' , [
	'icon' => 'bell' ,
	'items' => $topbar_notification_menu ,
	'counter' => $topbar_notification_menu['total']  ,
	'color' => 'coral'
	])
@endif
