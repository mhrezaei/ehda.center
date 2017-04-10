<span class="refresh">{{ url("manage/users/act/$model->id/receipts-table") }}</span>
{{ '' , $total = $model->receipts()->count() }}
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
		<td>{{ trans('front.purchased_at') }}</td>
		<td>
			{{ trans('validation.attributes.amount') }}
			<span class="f10 text-info mh5">
							({{ trans('cart.total:').' '.pd(number_format($model->total_receipts_amount/10)).' '.getSetting('currency') }})
						</span>
		</td>
		<td>{{ trans('validation.attributes.submitted_at') }}</td>
	</tr>
	</thead>

	{{--
	|--------------------------------------------------------------------------
	| New Form
	|--------------------------------------------------------------------------
	|
	--}}

	@include("manage.users.receipts-add")


	{{--
	|--------------------------------------------------------------------------
	| Browser
	|--------------------------------------------------------------------------
	|
	--}}

	@foreach($model->receipts()->orderBy('created_at' , 'desc')->get() as $key => $receipt)
		@include("manage.users.receipts-row")
	@endforeach

	{{--
	|--------------------------------------------------------------------------
	| no-results
	|--------------------------------------------------------------------------
	|
	--}}

	@if(!$total)
		<td colspan="4">
			<div class="no-results m20">
				{{ trans('cart.no_registered_receipt') }}
			</div>
		</td>
	@endif
</table>
