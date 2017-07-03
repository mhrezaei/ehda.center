@extends('manage.frame.use.0')

@section('section')

	@if(!$model->id)
		<div id="divInquiry">
			@include('manage.users.card-editor-inquiry')
		</div>
	@endif


@endsection