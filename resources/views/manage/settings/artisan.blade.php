@extends('manage.frame.use.0')

@section('section')
	@include('manage.settings.tabs-upstream')

	<div class="panel panel-black margin-auto w90" style="max-height: 1000px;margin-top: 30px;direction: ltr" >
		<div class="panel-footer" style="height: 50px">

			{!! Form::open([
				'url' => 'manage/upstream/save/artisan' ,
				'method' => 'post' ,
				'class' => 'form-inline js' ,
				'no-validation' => '1' ,
			]) !!}

				<div class="form-group" style="width: 100%">
					<input type="text" name="command" class="form-control" placeholder="Artisan Command" style="width: 100%" >
				</div>
				{{--<button type="submit" class="btn btn-primary">Run Artisan Command</button>--}}
			{!! Form::close() !!}

		</div>
		<div id="divArtisanResponse" class="panel-heading p20" style="min-height: 500px ; color: yellow">

		</div>
	</div>
@endsection