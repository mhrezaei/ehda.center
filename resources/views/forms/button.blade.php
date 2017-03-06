@if(!isset($condition) or $condition)
	<button
			id="{{ $id or '' }}"
			type="{{ $type or 'button' }}"
			name="_{{ $type or 'button' }}"
			value="{{ $value or '' }}"
			class="btn btn-{{$shape or 'default'}} {{$class or ''}} "
			@if(isset($link) and (str_contains($link , '(') or str_contains($link , ')')))
				onclick="{{$link}}"
			@elseif(isset($link))
				onclick="window.location ='{{ url($link) }}'"
			@endif
			{{ $extra or '' }}
	>
		{{$label or ''}}
	</button>
@endif