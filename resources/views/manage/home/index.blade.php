@extends('manage.frame.use.0')

@section('section')

	<div id="divEgo" class="w100">
		@include("manage.home.index-ego")
	</div>

	@if(user()->min(8)->is_admin())
		<div id="divCardsTimebar" class="w100" data-src="manage/widget/cards-line" data-loading="no">
			@include("manage.home.index-cards-line")
		</div>


		<div class="pinBoot noDisplay">
			@include("manage.home.index-widgets")
		</div>


		<div id="imgDashboardLoading" class="margin-auto text-center">
			@include("manage.frame.widgets.loading")
		</div>

		<script>setTimeout(function(){ $(".pinBoot , #imgDashboardLoading").slideToggle('fast')} , 1000)</script>
	@else
		<div class="w100">
			@include("manage.home.index-pending")
		</div>
	@endif

@endsection