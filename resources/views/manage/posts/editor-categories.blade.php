@if($model->has('category'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('validation.attributes.category') }}
		</div>

		<div class="panel-body">
			@foreach($model->posttype->folders()->orderBy('title')->get() as $key => $folder)
				<i class="noDisplay" aria-atomic="{{ booleanValue($categories = $folder->categories()->orderBy('title')) }}"></i>
				@if($categories->count())
					<div class="text-bold f11 {{ $key>0? "mv20" : "" }}">
						{{ $folder->title }}
						<div class="w100 mv5 mh20 text-normal">
							@foreach($categories->get() as $category)
								@include("forms.check" , [
									'name' => "category-$category->id",
									'title' => $category->title,
									'label' => $category->title,
									'value' => false,
								])
							@endforeach
						</div>
					@endif
				</div>
			@endforeach
		</div>


	</div>
@endif