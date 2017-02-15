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
			@if($models->count())
				@pd(trans('manage.grid_count' , [
					'rows' => $models->count() ,
					'total' => $models->total()
				]))
			@endif
		</div>
	</div>
@endif