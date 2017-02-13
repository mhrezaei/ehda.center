<div id="{{$id or ''}}" class="alert alert-{{$shape or 'info'}} w90 {{$div_class or ''}}">
	@foreach($texts as $text)
		<div class="row mv5">
			<div class="col-md-1 text-center">
				<i class="fa fa-{{$icon or 'hand-o-left'}} f15 {{$icon_class or ''}}"></i>
			</div>
			<div class="col-md-11 text-justify {{$text_class or ''}}">
				{{ $text }}
			</div>
		</div>
	@endforeach
</div>