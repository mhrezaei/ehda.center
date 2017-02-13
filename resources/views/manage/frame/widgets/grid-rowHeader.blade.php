@if(isset($refresh_url))
	<td class="refresh">{{ url($refresh_url) }}</td>
@endif
@if(isset($selector) and $selector)
	<td>
		<input id="gridSelector-{{$model->id}}" data-value="{{$model->id}}" class="gridSelector" type="checkbox" onchange="gridSelector('selector','{{$model->id}}')">
	</td>
@endif
@if(isset($counter) and $counter)
	<td class="-rowCounter">
		@if(isset($i))
			@pd($i+1)
		@endif
	</td>
@endif
