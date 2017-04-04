@extends('manage.frame.use.0')

@section('section')

	@include("manage.settings.categories-tabs")

	@if($type->exists)
		@include("manage.settings.categories-folder")
	@else
		@include("manage.settings.categories-types")
	@endif

@endsection