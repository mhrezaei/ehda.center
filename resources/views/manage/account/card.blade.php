@extends('manage.frame.use.0')

@section('section')
	@include("manage.account.tabs")

	<div class="mv30 margin-auto" style="max-width: 800px">
		@include("manage.account.card-inside")
	</div>

@endsection