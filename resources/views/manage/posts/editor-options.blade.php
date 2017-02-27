@if($model->hasAnyOf(['template_choice','slug','visibility_choice']))

	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('posts.form.options') }}
		</div>

		<div class="panel-body">

			{{--
			|--------------------------------------------------------------------------
			| Template
			|--------------------------------------------------------------------------
			|
			--}}


			@include("forms.select_self" , [
				'condition' => $model->has('template_choice'),
				'top_label' => trans('posts.form.template'),
				'name' => "template",
				'options' => $model->posttype->templatesCombo(),
				'value' => $model->template,
				'value_field' => "0",
				'caption_field' => "1",
			])


			{{--
			|--------------------------------------------------------------------------
			| Slug
			|--------------------------------------------------------------------------
			|
			--}}
			@include("forms.input-self" , [
				'condition' => $model->has('slug'),
				'top_label' => trans('validation.attributes.slug'),
				'name' => "slug",
				'value' => $model->slug,
				'class' => "ltr text-center",
				'placeholder' => "like_this",
			])

			{{--
			|--------------------------------------------------------------------------
			| Visibility
			|--------------------------------------------------------------------------
			|
			--}}
			<div class="m10"></div>
			@include("manage.posts.editor-visibility")


		</div>


	</div>
@endif