@if($model->has('locales'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('posts.features.locales') }}
		</div>

		<div class="panel-body">
			{{ $model->locale }}
		</div>


	</div>
@endif