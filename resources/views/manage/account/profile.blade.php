@extends('manage.frame.use.0')

@section('section')
	@include("manage.account.tabs")

	<div class="panel panel-default w80 mv30">
		<div class="panel-heading">
			<i class="fa fa-user-o"></i>
			<span class="mh5">
				{{trans('people.commands.profile')}}
			</span>
		</div>

		<div class="panel-body p10">
			@include("manage.account.profile-form")
		</div>
	</div>

@endsection