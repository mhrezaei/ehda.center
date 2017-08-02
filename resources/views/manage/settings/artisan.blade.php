@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	<div class="panel panel-black margin-auto w90" style="max-height: 1000px;margin-top: 30px;direction: ltr" >
		<div class="panel-footer" style="height: 50px">

			{!! Form::open([
				'url' => isset($url)? url($url) : '#' ,
				'method' => 'post' ,
				'class' => 'form-inline js' ,
				'no-validation' => '1' ,
			]) !!}

				<div class="form-group" style="width: 100%">
					<input type="text" class="form-control" placeholder="Artisan Command" style="width: 100%" >
				</div>
				{{--<button type="submit" class="btn btn-primary">Run Artisan Command</button>--}}
			{!! Form::close() !!}

		</div>
		<div class="panel-heading" style="min-height: 500px">

		</div>
	</div>
@endsection