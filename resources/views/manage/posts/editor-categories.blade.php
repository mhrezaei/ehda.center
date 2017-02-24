@if($model->has('category'))
	<div class="panel panel-default">
		<div class="panel-heading">
			{{ trans('validation.attributes.category') }}
		</div>

		<div class="panel-body">
			@foreach($model->posttype->folders()->orderBy('title')->get() as $key => $folder)
				<div class="text-bold f11 {{ $key>0? "mv20" : "" }}">
					{{ $folder->title }}
					<div class="w100 mv5 mh20 text-normal">
						@foreach($folder->categories()->orderBy('title')->get() as $category)
							@include("forms.check" , [
								'name' => "category-$category->id",
								'title' => $category->title,
								'label' => $category->title,
								'value' => false,
							])
						@endforeach
					</div>
				</div>
			@endforeach
		</div>


	</div>
@endif