@include("forms.feed" , [
	'div_class' => "m10",
])

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
		<td>{{ trans('cart.purchase') }}</td>
		<td>&nbsp;</td>
	</tr>
	</thead>

	{{--
	|--------------------------------------------------------------------------
	| New Form
	|--------------------------------------------------------------------------
	|
	--}}
	@if($model->isDrawingReady())
		@include("manage.posts.draw-winners-add")
	@endif


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
		<td colspan="5">
			<div class="no-results m20">
				{{ trans('cart.no_winner_so_far') }}
			</div>
		</td>
	@endif
</table>
