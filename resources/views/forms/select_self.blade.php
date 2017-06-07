@if(!isset($condition) or $condition)

	@if(isset($top_label))
		<label for="{{$name}}" class="control-label text-gray {{$top_label_class or ''}}" >{{ $top_label }}...</label>
	@endif

	<select
		id="{{$id or ''}}"
		name="{{$name}}" value="{{$value or ''}}"
		class="form-control selectpicker {{$class or ''}}"
		placeholder="{{$placeholder or ''}}"
		data-size= "{{$size or 5}}"
		data-live-search = "{{$search or false}}"
		data-live-search-placeholder= "{{$search_placeholder or trans('forms.button.search')}}..."
		onchange="{{$on_change or ''}}"
		{{$extra or ''}}
	>
		@if(isset($blank_value) and $blank_value!='NO')
			<option value="{{$blank_value}}"
					@if(!isset($value) or $value==$blank_value)
						selected
					@endif
			>{{ $blank_label or '' }}</option>
		@endif
		@foreach($options as $option)
			<option value="{{$option[isset($value_field)? $value_field : 'id']}}"
					@if(isset($value) and $value==$option[isset($value_field)? $value_field : 'id'])
						selected
					@endif
			>{{$option[isset($caption_field)? $caption_field : 'title']}}</option>
		@endforeach
	</select>

	@include("forms.js" , [
		'commands' => [
			isset($on_change) and (!isset($initially_run_onchange) or $initially_run_onchange) ? [$on_change] : [],
		]
	])
@endif