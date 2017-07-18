@extends('manage.frame.use.0')

@section('section')

	@if(!$model->code_melli)
		<div id="divInquiry">
			@include('manage.users.card-editor-inquiry')
		</div>
	@endif
	<div id="divForm" class="{{ $model->code_melli ? '' : 'noDisplay' }}">
		@include("manage.users.card-editor-form")
	</div>
	<div id="divCard" class="noDisplay text-center" data-src="manage/users/act/-id-/card-editor-show" data-id="">
	</div>


@endsection