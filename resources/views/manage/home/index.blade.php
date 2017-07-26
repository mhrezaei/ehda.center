@extends('manage.frame.use.0')

@section('section')

	<div id="divEgo" class="w100">
		@include("manage.home.index-ego")
	</div>

	<div id="divCardsTimebar" class="w100" data-src="manage/widget/cards-line" data-loading="no">
		@include("manage.home.index-cards-line")
	</div>





	<div class="pinBoot noDisplay">
		@include("manage.home.index-widgets")
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