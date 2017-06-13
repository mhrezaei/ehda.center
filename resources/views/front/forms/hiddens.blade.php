@foreach($fields as $field)
	<input type="hidden" id="{{$field[2] or ''}}" name="{{$field[0]}}" value="{{$field[1] or ''}}">
@endforeach