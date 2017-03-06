@if(!isset($condition) or $condition)
	<input
		type="hidden"
		id="{{$id or ''}}"
		name="{{$name}}"
		value="{{$value or ''}}"
		{{$extra or ''}}
	>
@endif