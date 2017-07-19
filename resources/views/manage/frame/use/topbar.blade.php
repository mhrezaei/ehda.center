
<span id="spnTopbarNotification" data-src="manage/widget/topbar-notification" ondblclick="divReload('spnTopbarNotification')">
	<i class="fa fa-bell-o text-gray"></i>
	{{--<script>divReload('spnTopbarNotification')</script> --}}
	{{-- @TODO: Uncomment the above line, and remove this line, on production. --}}
</span>

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
