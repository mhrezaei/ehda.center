@if($model->has('category'))
	<div id="divCategories" class="panel panel-default noDisplay">
		<div class="panel-heading">
			{{ trans('validation.attributes.category') }}
		</div>

		<div class="panel-body"  style="max-height: 200px;overflow-y: scroll;overflow-x: hidden">
			{{ '' , $printed = 0 }}

			{{--
			|--------------------------------------------------------------------------
			| Categories withoug folders
			|--------------------------------------------------------------------------
			|
			--}}



			{{--
			|--------------------------------------------------------------------------
			| Categories under folders
			|--------------------------------------------------------------------------
			|
			--}}

			@foreach($model->posttype->folders()->where('locale',$model->locale)->orderBy('title')->get() as $key => $folder)
					<i class="noDisplay" aria-atomic="{{ booleanValue($categories = $folder->categories()->orderBy('title')) }}"></i>
					@if($categories->count())
						<div class="text-bold f11 {{ $key>0? "mv20" : "" }}">
							{{ $folder->title }}
							<div class="w100 mv5 mh20 text-normal">
								@foreach($categories->get() as $category)
									@include("forms.check" , [
										'name' => "category-$category->hash_id",
										'title' => $category->title,
										'label' => $category->title,
										'value' => $model->isUnder($category)? true : false ,
										'printed' => $printed++ ,
									])
								@endforeach
							</div>
						</div>
					@endif
				@endforeach
		</div>

	</div>
	<script>postToggleCategories({{ $printed }})</script>
@endif