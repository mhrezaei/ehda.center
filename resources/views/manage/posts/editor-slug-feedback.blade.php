@if(!$suggested_slug)
	<span class="text-success f10">
		<i class="fa fa-smile-o"></i>
		{{ trans('posts.form.no_slug') }}
	</span>

@elseif(!$approved_slug)
	<span class="text-danger f10">
		<i class="fa fa-times"></i>
		{{ trans('posts.form.invalid_slug') }}
	</span>

@elseif($approved_slug == $suggested_slug)
	<span class="text-success f10">
		<i class="fa fa-check"></i>
		{{ trans('posts.form.valid_slug') }}
	</span>

@else
	<span class="text-orange f10">
		<i class="fa fa-exclamation-triangle"></i>
		{{ trans('posts.form.slug_will_be_changed_to' , ['approved_slug' => $approved_slug]) }}
	</span>

@endif