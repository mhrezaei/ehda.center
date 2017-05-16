@if(!isset($condition) or $condition)
	<div>
		<div>
			<hr class="separator {{$class or ''}}">
			@if(isset($label))
				<div class="{{ $class or 'text-grey' }}" style="margin-bottom: 20px">{{$label}}...</div>
			@endif
		</div>
	</div>
@endif