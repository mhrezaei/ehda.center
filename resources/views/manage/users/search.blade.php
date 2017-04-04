@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.users.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
	])


	<div class="panel panel-default m20">

		@include('forms.opener',[
			'url' => "manage/users/browse/$request_role/search" ,
			'class' => 'js-' ,
			'method' => 'get',
		])

		<br>

		@include('forms.hiddens' , ['fields' => [
			['searched' , 1],
		]])

		@include('forms.input' , [
			'name' => 'keyword',
			'class' => 'form-required form-default'
		])

		@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('forms.button.search'),
			'shape' => 'success',
			'type' => 'submit' ,
		])

		@include('forms.group-end')

		@include('forms.feed' , [])

		@include('forms.closer')
	</div>

@endsection


