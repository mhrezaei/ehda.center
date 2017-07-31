<div class="panel panel-orange">
	@if(!isset($topbar_notification_menu))
		{{ '' , $topbar_notification_menu = Manage::topbarNotificationMenu() }}
	@endif

	<div class="panel-heading">
		<i class="fa fa-bell"></i>
		<span class="mh5">
			{{ trans("forms.status_text.action_required") }} ( {{ pd($topbar_notification_menu['total']) }} )
		</span>
		<span class="pull-left">
			<i class="fa fa-refresh clickable" onclick="divReload('divNotifications')"></i>
		</span>
	</div>


	<div class="panel-footer">


		@foreach($topbar_notification_menu as $key => $item)
			@if($key === 'total')
			@elseif($item[0] == '-' )
				<hr style="border: orange 1px dashed">
			@else
				<div class="m10" >
					<a href= {{ url($item[0]) }}>
						<i class="fa fa-{{$item[2] or ''}}"></i>
						<span class="mh5">
							{{ $item[1] or '---' }}
						</span>
					</a>
				</div>
			@endif
		@endforeach
	</div>

</div>