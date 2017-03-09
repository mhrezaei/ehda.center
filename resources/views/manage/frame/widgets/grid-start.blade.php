<div class="panel panel-default m20">
	<div class="panel-body">
		<table id="{{$table_id or ''}}" class="table tableGrid {{$table_class or 'table-hover'}}">
			<thead>
			<tr>
				@if(isset($handle) and str_contains($handle , 'selector'))
					<td width="50">
						<input type="checkbox" id="gridSelector-all" onchange="gridSelector('all')">
					</td>
				@endif
					@if(isset($handle) and str_contains($handle , 'counter'))
					<td width="50">#</td>
				@endif
				@foreach($headings as $heading)
					<?php
							if(is_array($heading)) {
								$switches= $heading ;
								$heading = $switches[0];
								// ~~> 1:width 2:condition <~~
							}
							else {
								$switches = [] ;
							}
					?>
					@if($heading != 'NO' and ( !isset($switches[2]) or $switches[2] ))
						<td width="{{$switches[1] or ''}}">{{ $heading }}</td>
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