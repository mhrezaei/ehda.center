{{--
|--------------------------------------------------------------------------
| Title...
|--------------------------------------------------------------------------
| If feature 'long_title' is given, a mini tiny is shown, instead of a simple text box
--}}

@if($model->hasnot('long_title'))
	@include("forms.input-self" , [
		'name' => "title",
		'value' => $model->title,
		'class' => "form-required form-default",
		'placeholder' => trans('posts.form.title_placeholder'),
	])
@else
	@include("forms.textarea" , [
		'name' => "long_title",
		'value' => "$model->title",
		'class' => "form-required tinyMini",
		'placeholder' => trans('posts.form.title_placeholder'),
		'in_form' => false,
	])
@endif

{{--
|--------------------------------------------------------------------------
| Second Title...
|--------------------------------------------------------------------------
|
--}}
@if($model->has('title2'))
	<a id="lblTitle2"
	   href="javascript:void(0)"
	   onclick="postToggleTitle2()"
	   class="btn btn-link btn-xs displayBlock {{$model->title2? "noDisplay":''}}"
	>
		{{ trans('posts.form.add_second_title') }}
	</a>
	@include("forms.input-self" , [
		'id' => "txtTitle2",
		'name' => "title2",
		'value' => $model->title2,
		'class' => $model->title2? "" : "noDisplay",
		'placeholder' => trans('posts.form.title2_placeholder'),
	])
@endif

{{--
|--------------------------------------------------------------------------
| Text
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

@if($model->has('abstract'))
	<label for="abstract" class="control-label mv10 text-gray" >{{ trans('validation.attributes.abstract') }}...</label>
	@include("forms.textarea" , [
		'name' => "abstract",
		'value' => $model->abstract,
		'rows' => "5",
		'in_form' => false,
	])
@endif