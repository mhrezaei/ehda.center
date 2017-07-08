@if($model->hasAnyOf(['template_choice','slug','visibility_choice' , 'comment' , 'domains']))

	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('posts.form.options') }}
		</div>

		<div class="panel-body">

			{{--
			|--------------------------------------------------------------------------
			| Domains
			|--------------------------------------------------------------------------
			| //@TODO: Check Users's Privilages before showing the menu
			--}}
			@include("manage.posts.editor-domains")
			<div class="m10"></div>

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
				'placeholder' => "like-this",
				'on_blur' => "postsAction('check_slug')",
				'id' => "txtSlug",
			])
			<div id="divSlugFeedback"></div>

			{{--
			|--------------------------------------------------------------------------
			| Visibility
			|--------------------------------------------------------------------------
			|
			--}}
			<div class="m10"></div>
			@include("manage.posts.editor-visibility")

			{{--
			|--------------------------------------------------------------------------
			| Comments
			|--------------------------------------------------------------------------
			|
			--}}
			<div class="m10"></div>
			@include("manage.posts.editor-comments")


			{{--
			|--------------------------------------------------------------------------
			| Persian Digits
			|--------------------------------------------------------------------------
			|@TODO: find a way to replace the strings, without touching the inline attributes.
			--}}
			{{--@include("forms.check" , [--}}
				{{--'condition' => in_array($model->locale , ['fa' , 'ar']) ,--}}
				{{--'name' => "disable_receiving_comments",--}}
				{{--'label' => trans('posts.form.automatically_change_english_digits') ,--}}
				{{--'value' => $model->automatically_change_english_digits ,--}}
			{{--])--}}

		</div>


	</div>
@endif