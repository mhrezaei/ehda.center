@extends('manage.frame.use.0')

@section('section')

	<div class="row">
		<div id="divCardsByGender" class="col-md-3" data-src="manage/widget/cards-pie" data-loading="no">
			@include("manage.home.index-cards-pie")
		</div>
		<div id="divCardsTimebar" class="col-md-9" data-src="manage/widget/cards-line"  data-loading="no">
			@include("manage.home.index-cards-line")
		</div>
	</div>


	{{--<div class="row">--}}
		{{--@foreach($digests as $digest)--}}
			{{--@include('manage.frame.widgets.digest' , $digest)--}}
		{{--@endforeach--}}
	{{--</div>--}}



@endsection