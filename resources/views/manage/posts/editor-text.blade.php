{{--
|--------------------------------------------------------------------------
| Main Text
|--------------------------------------------------------------------------
|
--}}
@if($model->has('text'))
	<div class="mv20">
		@include("forms.textarea" , [
			'name' => "title",
			'value' => "$model->title",
			'class' => "form-required tinyEditor",
			'rows' => "15",
			'in_form' => false,
		])
	</div>
@endif

{{--
|--------------------------------------------------------------------------
| Abstract
|--------------------------------------------------------------------------
|
--}}

@if($model->has('abstract'))
	<div class="mv10">
		@include("forms.textarea" , [
			'top_label' => trans('validation.attributes.abstract'),
			'top_label_class' => "mv10",
			'name' => "abstract",
			'value' => $model->abstract,
			'rows' => "5",
			'in_form' => false,
		])
	</div>
@endif