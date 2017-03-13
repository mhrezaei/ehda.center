<ul class="nav nav-tabs">
	<li class="{{$type->exists? '' : 'active'}}">
		<a href="{{ $base = url("manage/categories") }}">{{ trans("posts.categories.meaning") }}</a>
	</li>
	@foreach($posttypes as $posttype)
		@if(user()->as('admin')->can("posts-$posttype->slug"))

			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="fa fa-{{ $posttype->spreadMeta()->icon }}"></span>
					{{ $posttype->title }}
					<span class="fa fa-caret-down mh5"></span>
				</a>
				<ul class="dropdown-menu">
					@foreach($posttype->locales_array as $lang)
						<li>
							<a href="{{ "$base/browse/$posttype->slug/$lang" }}">
								<img src="{{ asset("assets/images/lang-$lang.png") }}" style="width: 20px;margin-left: 5px">
								{{ trans("forms.lang.$lang") }}
							</a>
						</li>
					@endforeach
				</ul>
			</li>

		@endif
	@endforeach
</ul>