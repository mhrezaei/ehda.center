@if($model->has('locales'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('posts.features.locales') }}
		</div>

		<div class="panel-body">

			@include("manage.posts.editor-locales-inside")

		</div>


	</div>
@endif

@include("forms.hidden" , [
	'name' => "locale",
	'value' => $model->locale,
	'id' => "txtLocale",
])