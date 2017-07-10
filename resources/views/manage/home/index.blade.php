@extends('manage.frame.use.0')

@section('section')

	<div id="divCardsTimebar" class="w100" data-src="manage/widget/cards-line"  data-loading="no">
		@include("manage.home.index-cards-line")
	</div>





	<div class="pinBoot">



		{{-- Search People --}}
		<div id="divSearchPeople" class="pinBoot-inside">
			@include("manage.home.index-search-people")
		</div>


		{{-- Card Pie-Chart, by Gender --}}
		<div id="divCardsByGender2" class="pinBoot-inside" data-src="manage/widget/cards-pie2" data-loading="no">
			@include('manage.home.index-cards-pie2')
		</div>



	</div>



	{{--<div class="row">--}}
		{{--@foreach($digests as $digest)--}}
			{{--@include('manage.frame.widgets.digest' , $digest)--}}
		{{--@endforeach--}}
	{{--</div>--}}



@endsection