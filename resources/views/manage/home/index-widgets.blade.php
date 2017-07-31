{{--
|--------------------------------------------------------------------------
| Card Pie-Chart, by Gender
|--------------------------------------------------------------------------
|
--}}

<div id="divCardsByGender" class="pinBoot-inside" data-src="manage/widget/cards-pie" data-loading="no">
	@include('manage.home.index-cards-pie')
</div>

{{--
|--------------------------------------------------------------------------
| Card Pie-Chart, by Bot Actions
|--------------------------------------------------------------------------
|
--}}

<div id="divCardsByMedia" class="pinBoot-inside" data-src="manage/widget/cards-media" data-loading="no">
	@include('manage.home.index-cards-media')
</div>



{{--
|--------------------------------------------------------------------------
| Volunteers
|--------------------------------------------------------------------------
|
--}}

<div id="divVolunteers" class="pinBoot-inside" data-src="manage/widget/volunteers" data-loading="no">
	@include("manage.home.index-volunteers")
</div>



{{--
|--------------------------------------------------------------------------
| Search People
|--------------------------------------------------------------------------
|
--}}

<div id="divSearchPeople" class="pinBoot-inside">
	@include("manage.home.index-search-people")
</div>






{{--
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
|
--}}

{{ '' , $topbar_notification_menu = Manage::topbarNotificationMenu() }}
@if($topbar_notification_menu['total'])
	<div id="divNotifications" class="pinBoot-inside" data-src="manage/widget/notifications">
		@include("manage.home.index-notifications")
	</div>
@endif






{{--
|--------------------------------------------------------------------------
| Create Post
|--------------------------------------------------------------------------
|
--}}

@if(sizeof($topbar_create_menu = Manage::topbarCreateMenu())>1)
	<div id="divCreate" class="pinBoot-inside">
		@include("manage.home.index-create")
	</div>
@endif



