@if(!isset($condition) or $condition)
	<div class="alert alert-{{$shape or 'info'}} form-note {{$class or ''}} " role="alert">
		<i class="fa fa-{{$icon or 'exclamation-circle'}}">
		</i>
		@pd($text)
	</div>
@endif
