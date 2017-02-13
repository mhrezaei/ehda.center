@if(0)
	<div>
		<div>
			<table>
				<tbody>
				@endif


				@if(1)
				</tbody>
			</table>
		</div>
		<div class="grid_count">
			@if($model_data->count())
				@pd(trans('manage.grid_count' , [
					'rows' => $model_data->count() ,
					'total' => $model_data->total()
				]))
			@endif
		</div>
	</div>
@endif