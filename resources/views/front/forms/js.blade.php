@if(!isset($condition) or $condition)

	@foreach($commands as $command)
		<input type="hidden" class="js" data-delay="{{$command[1] or 1 }}" value="{{$command[0] or ''}}">
	@endforeach

@endif