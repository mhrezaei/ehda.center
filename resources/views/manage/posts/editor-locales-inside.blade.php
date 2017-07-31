@foreach($model->posttype->locales_array as $locale)
	{{ '' , $sister = $model->in($locale) }}
	<div class="row mv15">
		<div class="col-md-1">
			<img src="{{ asset("assets/images/lang-$locale.png") }}" style="width: 20px">
		</div>
		<div class="col-md-3">
			{{ trans("forms.lang.$locale") }}
		</div>
		<div class="col-md-7">
			@if($locale == $model->locale)
				<i class="text-success">{{ trans('posts.form.this_page') }}</i>
				<i class="fa fa-check-circle text-success"></i>
			@elseif($sister->exists)
				@include("manage.frame.widgets.grid-text" , [
					'text' => trans("forms.status_text.$sister->status"),
					'link' => "urlN:".$sister->browse_link , // $sister->canEdit()? $sister->edit_link : '',
					'color' => trans("forms.status_color.".$sister->status),
				])
			@else
				@if($model->can("create.$locale"))
					<a class="btn btn-default btn-xs minWidthAuto w100" href="{{ $sister->create_link }}">{{ trans('validation.attributes.create') }}</a>
				@else
					<span class="text-gray f10">{{ trans("forms.status_text.so_far_absent") }}</span>
				@endif
			@endif
		</div>
	</div>
@endforeach
