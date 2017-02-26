@if($model->has('locales'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('posts.features.locales') }}
		</div>

		<div class="panel-body">

			@foreach($model->posttype->locales_array as $locale)
				{{ '' , $sister_model = $model->in($locale) }}
				<div class="row mv15">
					<div class="col-md-1">
						<img src="{{ asset("assets/images/lang-$locale.png") }}" style="width: 20px">
					</div>
					<div class="col-md-3">
						{{ trans("forms.lang.$locale") }}
					</div>
					<div class="col-md-7">
						@if($locale == $model->locale)
							<i class="text-gray">{{ trans('posts.form.this_page') }}</i>
							<i class="fa fa-check-circle text-success"></i>
						@elseif($sister_model->exists)
							@include("manage.frame.widgets.grid-text" , [
								'text' => trans("forms.status_text.$sister_model->status"),
								'link' => $sister_model->canEdit()? $sister_model->edit_link : '',
								'color' => trans("forms.status_color.".$sister_model->status),
							])
						@else
							@if($model->can('create'))
								<a class="btn btn-default btn-xs minWidthAuto w100" href="{{ $sister_model->create_link }}">{{ trans('validation.attributes.create') }}</a>
							@else
								<span class="text-gray">{{ trans("forms.status_text.so_far_absent") }}</span>
							@endif
						@endif
					</div>
				</div>
			@endforeach


			{{--
			|--------------------------------------------------------------------------
			| This Post...
			|--------------------------------------------------------------------------
			|
			--}}

			{{--<hr>--}}
			{{--@include("forms.input-self" , [--}}
				{{--'name' => '_locale',--}}
				{{--'top_label' => trans('validation.attributes.post_locale'),--}}
				{{--'top_label_style' => "",--}}
				{{--'value' => trans("forms.lang.$model->locale"),--}}
				{{--'extra' => "disabled",--}}
			{{--])--}}
			
		</div>


	</div>
@endif

@include("forms.hidden" , [
	'name' => "locale",
	'value' => $model->locale,
])