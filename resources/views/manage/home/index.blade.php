@extends('manage.frame.use.0')

@section('section')

	<div id="divCardsTimebar" class="w100" data-src="manage/widget/cards-line"  data-loading="no">
		@include("manage.home.index-cards-line")
	</div>





	<div class="pinBoot noDisplay" >



		{{-- Search People --}}
		<div id="divSearchPeople" class="pinBoot-inside">
			@include("manage.home.index-search-people")
		</div>


		{{-- Card Pie-Chart, by Gender --}}
		<div id="divCardsByGender" class="pinBoot-inside" data-src="manage/widget/cards-pie" data-loading="no">
			@include('manage.home.index-cards-pie')
		</div>


		{{-- Notifications --}}
		@if(sizeof($topbar_notification_menu = Manage::topbarNotificationMenu() )>1)
			<div id="divNotifications" class="pinBoot-inside" data-src="manage/widget/notifications">
				@include("manage.home.index-notifications")
			</div>
		@endif

		{{-- Volunteers --}}
		<div id="divVolunteers" class="pinBoot-inside" data-src="manage/widget/volunteers" data-loading="no">
			@include("manage.home.index-volunteers")
		</div>


	</div>



	{{--<div class="row">--}}
		{{--@foreach($digests as $digest)--}}
			{{--@include('manage.frame.widgets.digest' , $digest)--}}
		{{--@endforeach--}}
	{{--</div>--}}

	<div id="imgDashboardLoading" class="margin-auto text-center">
		@include("manage.frame.widgets.loading")
	</div>
	<script>setTimeout(function(){ $(".pinBoot , #imgDashboardLoading").slideToggle('fast')} , 1000)</script>
@endsection