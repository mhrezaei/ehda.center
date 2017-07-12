@if(sizeof($topbar_notification_menu = Manage::topbarNotificationMenu() )>1)
	@include('manage.frame.widgets.topbar' , [
	'icon' => 'bell' ,
	'items' => $topbar_notification_menu ,
	'counter' => $topbar_notification_menu['total']  ,
	'color' => 'coral'
	])
@endif
