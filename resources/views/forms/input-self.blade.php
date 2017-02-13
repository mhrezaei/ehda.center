@if(!isset($condition) or $condition)
	<input
		type="{{$type or 'text'}}"
		id="{{$id or ''}}"
		name="{{$name}}" value="{{$value or ''}}"
		class="form-control {{$class or ''}}"
		placeholder="{{$placeholder or ''}}"
		onkeyup="{{$on_change or ''}}"
		onblur="{{$on_blur or ''}}"
		onfocus="{{$on_focus or ''}}"
		aria-valuenow="{{$value or ''}}"
		{{$extra or ''}}
	>
@endif
