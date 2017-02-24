@extends('manage.frame.use.0')

@section('section')

	{!! \App\Providers\DummyServiceProvider::englishWord(50) !!}

	{{--{{ getLocale() }}--}}
{{--	@include('manage.index.hello')--}}

	{{--<div class="row">--}}
		{{--@foreach($digests as $digest)--}}
			{{--@include('manage.frame.widgets.digest' , $digest)--}}
		{{--@endforeach--}}
	{{--</div>--}}

	{{--@include('templates.say' , ['array'=>user()->as('user')->role()->toArray()])--}}
	{{--@include('templates.say' , ['array'=>user()->as('user')->can()])--}}
	{{--@include('templates.say' , ['array'=>user()->enableRole('user')->shh()])--}}
@endsection