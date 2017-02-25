<div class="panel panel-default mv20">
	{{--<div class="panel-heading">--}}
{{--			{{ trans('validation.attributes.price') }}--}}
	{{--</div>--}}

	<div class="panel-body">

		@foreach($model->posttype->optional_meta_array as $field)
			@include("manage.frame.widgets.input-".$field['type'] , [
				'name' => $field_name = $field['name'],
				'value' => $model->$field_name,
				'class' => $field['required']? 'form-required' : '',
			])
		@endforeach

	</div>
</div>