@if($meta_array = $model->posttype->optional_meta_array)
	<div class="panel panel-default mv20">
		{{--<div class="panel-heading">--}}
	{{--			{{ trans('validation.attributes.price') }}--}}
		{{--</div>--}}

		<div class="panel-body">
			{{ '' , $required_fields = '' }}

			@foreach($model->posttype->optional_meta_array as $field)
				@if($field['required'])
					{{ '' , $required_fields .= $field['name'].',' }}
				@endif

				@include("manage.frame.widgets.input-".$field['type'] , [
					'name' => $field_name = $field['name'],
					'value' => $model->$field_name,
					'class' => $field['required']? 'form-required' : '',
				])
			@endforeach

		</div>
	</div>
@endif

{{--
|--------------------------------------------------------------------------
| Required Meta Fields
|--------------------------------------------------------------------------
| This is to help Request file to control the mandatory meta entries
--}}
@include("forms.input-self" , [
	'name' => "_meta_required_fields",
	'value' => isset($required_fields)? encrypt($required_fields) : encrypt(''),
	'type' => "hidden",
])
