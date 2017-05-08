{{--
|--------------------------------------------------------------------------
| Main Text
|--------------------------------------------------------------------------
|
--}}
@if($model->has('text'))
	<div class="mv20">
		@include("forms.textarea" , [
			'id' => "txtText" ,
			'name' => "text",
			'value' => $model->text,
			'class' => "tinyEditor",
			'rows' => "15",
			'in_form' => false,
			'extra' => "onchange=postFormChange" ,
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