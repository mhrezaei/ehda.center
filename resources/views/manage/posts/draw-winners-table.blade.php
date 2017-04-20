<span class="refresh">{{ url("manage/posts/act/$model->id/draw-winners-table") }}</span>
<table class="table table-striped">

	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	|
	--}}

	<thead>
	<tr>
		<td>#</td>
		<td>{{ trans('validation.attributes.name_first') }}</td>
		<td>{{ trans('validation.attributes.mobile') }}</td>
	</tr>
	</thead>

	{{--
	|--------------------------------------------------------------------------
	| New Form
	|--------------------------------------------------------------------------
	|
	--}}
	@include("manage.posts.draw-winners-add")


	{{--
	|--------------------------------------------------------------------------
	| Browser
	|--------------------------------------------------------------------------
	|
	--}}

	@foreach($model->winners_array as $key => $winner)
		@include("manage.posts.draw-winners-row")
	@endforeach

	{{--
	|--------------------------------------------------------------------------
	| no-results
	|--------------------------------------------------------------------------
	|
	--}}

	@if(!count($model->winners_array))
		<td colspan="4">
			<div class="no-results m20">
				{{ trans('cart.no_winner_so_far') }}
			</div>
		</td>
	@endif
</table>
