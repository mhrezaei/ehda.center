{{--
|--------------------------------------------------------------------------
| First Title...
|--------------------------------------------------------------------------
| If feature 'long_title' is given, a mini tiny is shown, instead of a simple text box
--}}

@if($model->hasnot('long_title'))
	@include("forms.input-self" , [
		'name' => "title",
		'value' => $model->title,
		'class' => "form-required form-default atr",
		'placeholder' => trans('posts.form.title_placeholder'),
	])
@else
	@include("forms.textarea" , [
		'name' => "title",
		'value' => "$model->title",
		'class' => "form-required form-autoSize tinyMini-",
		'rows' => "2",
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
	<div id="lblTitle2" class="text-left">
		<a
				href="javascript:void(0)"
				onclick="postToggleTitle2()"
				class="btn btn-link btn-xs {{$model->title2? "noDisplay":''}} f10"
				style="position: relative;top: -20px;"
		>
			{{ trans('posts.form.add_second_title') }}
		</a>
	</div>

	<div  id="txtTitle2-container"  class="{{ $model->title2? "" : "noDisplay" }}">
		<div class="input-group">

			@include("forms.input-self" , [
				'id' => "txtTitle2",
				'name' => "title2",
				'value' => $model->title2,
				'placeholder' => trans('posts.form.title2_placeholder'),
			])
			<span class="input-group-addon clickable" onclick="postToggleTitle2()"><i class="fa fa-times f10 text-light "></i></span>
		</div>
	</div>
@endif