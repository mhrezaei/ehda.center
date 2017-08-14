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
			@if($models->count() and method_exists($models , 'total') )
				{{ trans("manage.grid_count" , [
					'rows' => pd($models->count()) ,
					'total' => pd($models->total()) ,
				]) }}
			@endif
		</div>
	</div>
@endif