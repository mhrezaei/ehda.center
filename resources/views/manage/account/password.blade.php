@extends('manage.frame.use.0')

@section('section')
	@include("manage.account.tabs")

	<div class="panel panel-default w80 mv30">
		<div class="panel-heading">
			<i class="fa fa-key"></i>
			<span class="mh5">
				{{trans('people.commands.change_password')}}
			</span>
		</div>
		<div class="panel-body p10">
			@include('forms.opener',[
				'url' => url('manage/account/save/password') ,
				'class' => "js",
			])

			@include('forms.input' , [
				'name' => '',
				'label' => trans('validation.attributes.name_first'),
				'value' => user()->full_name ,
				'disabled' => true ,
			])

			@include("forms.input" , [
				'name' => "current_password",
				'class' => "form-required ltr",
				'type' => "password",
			])
			@include("forms.input" , [
				'name' => "new_password",
				'class' => "form-required ltr",
				'type' => "password",
			])
			@include("forms.input" , [
				'name' => "password2",
				'class' => "form-required ltr",
				'type' => "password",
			])

			@include('forms.group-start')

			@include('forms.button' , [
				'label' => trans('forms.button.save'),
				'shape' => 'primary',
				'type' => 'submit' ,
			])
			@include('forms.button' , [
				'label' => trans('forms.button.cancel'),
				'shape' => 'link',
				'link' => 'window.history.back()',
			])

			@include('forms.group-end')

			@include('forms.feed')

			@include("forms.closer")
		</div>
	</div>

@endsection