@if($model->has('featured_image'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('validation.attributes.featured_image') }}
		</div>

		<div class="panel-body">
			@include("forms.hidden" , [
				'name' => "featured_image",
				'value' => "/img",
			])
		</div>


	</div>
@endif