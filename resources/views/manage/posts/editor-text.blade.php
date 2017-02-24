{{--
|--------------------------------------------------------------------------
| Main Text
|--------------------------------------------------------------------------
|
--}}
@if($model->has('text'))
	<label for="abstract" class="control-label mv10 text-gray" ></label>
	@include("forms.textarea" , [
		'name' => "title",
		'value' => "$model->title",
		'class' => "form-required tinyEditor",
		'rows' => "15",
		'in_form' => false,
	])
@endif

{{--
|--------------------------------------------------------------------------
| Abstract
|--------------------------------------------------------------------------
|
--}}

@if($model->has('abstract'))
	<label for="abstract" class="control-label mv10 text-gray" >{{ trans('validation.attributes.abstract') }}...</label>
	@include("forms.textarea" , [
		'name' => "abstract",
		'value' => $model->abstract,
		'rows' => "5",
		'in_form' => false,
	])
@endif