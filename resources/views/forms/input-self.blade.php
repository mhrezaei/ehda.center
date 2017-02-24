@if(!isset($condition) or $condition)
	@if(isset($top_label))
		<label for="{{$name}}" class="control-label text-gray {{$top_label_class or ''}}" >{{ $top_label }}...</label>
	@endif
	@if(isset($addon))
		<div class="input-group {{ $group_class or '' }}">
	@endif
	<input
		type="{{$type or 'text'}}"
		id="{{$id or ''}}"
		name="{{$name}}" value="{{$value or ''}}"
		class="form-control {{$class or ''}}"
		style="{{$style or ''}}"
		placeholder="{{$placeholder or ''}}"
		onkeyup="{{$on_change or ''}}"
		onblur="{{$on_blur or ''}}"
		onfocus="{{$on_focus or ''}}"
		aria-valuenow="{{$value or ''}}"
		{{$extra or ''}}
	>
	@if(isset($addon))
		<span class="input-group-addon f10 {{$addon_class or ''}}" onclick="{{$addon_click or ''}}">{{ $addon }}</span>
		</div>
	@endif

@endif
