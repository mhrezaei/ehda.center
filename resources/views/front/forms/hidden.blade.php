@if(!isset($condition) or $condition)
	<input
		type="hidden"
		id="{{$id or ''}}"
		name="{{$name}}"
		value="{{$value or ''}}"
		class="{{$class or ''}}"
		{{$extra or ''}}
	>
@endif