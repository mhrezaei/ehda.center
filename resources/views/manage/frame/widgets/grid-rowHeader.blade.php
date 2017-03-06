@if(isset($refresh_url))
	<td class="refresh">{{ url($refresh_url) }}</td>
@endif
@if(isset($handle) and str_contains($handle , 'selector'))
	<td>
		<input id="gridSelector-{{$model->id}}" data-value="{{$model->id}}" class="gridSelector" type="checkbox" onchange="gridSelector('selector','{{$model->id}}')">
	</td>
@endif
@if(isset($handle) and str_contains($handle , 'counter'))
	<td class="-rowCounter">
		@if(isset($i))
			@pd($i+1)
		@endif
	</td>
@endif
