@extends('manage.frame.use.0')

@section('section')

	@if(!$model->id)
		<div id="divInquiry">
			@include('manage.users.volunteer-editor-inquiry' )
		</div>
	@endif
	<div id="divForm" class="{{ $model->id? '' : 'noDisplay' }}">
		FORM
{{--		@include("manage.users.card-editor-form")--}}
	</div>
	<div id="divCard" class="noDisplay" data-src="manage/users/act/-id-/volunteer-editor-show/{{$request_role}}" data-id="">
		SHOW
	</div>


@endsection