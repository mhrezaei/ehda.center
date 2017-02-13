<div class="panel panel-default m20">
	<div class="panel-body">
		<table id="{{$table_id or ''}}" class="table tableGrid {{$table_class or 'table-hover'}}">
			<thead>
			<tr>
				@if(isset($selector) and $selector)
					<td>
						<input type="checkbox" id="gridSelector-all" onchange="gridSelector('all')">
					</td>
				@endif
				@if(isset($counter) and $counter)
					<td>#</td>
				@endif
				@foreach($headings as $heading)
					@if($heading != 'NO')
						<td>{{ $heading }}</td>
					@endif
				@endforeach
			</tr>
			</thead>
			<tbody>



			@if(0)
			</tbody>
		</table>
	</div>
</div>
@endif