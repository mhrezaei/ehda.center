@if(!isset($condition) or $condition)

	@include("forms.group-start" , [
		'fake' => !isset($name)? $name = 'icon' : '',
		'fake2' => !isset($value)? $value = '' : '',
		'label' => isset($label)? $label : trans("validation.attributes.$name"),
		'unselected_class' => isset($unselected_class)? $unselected_class : $unselected_class ='text-black f18',
		'selected_class' => isset($selected_class)? $selected_class : $selected_class = 'text-primary f22',
	])

	<div class="row">
		@foreach($icons as $icon)
			<div class="col-lg-1 col-md-2 col-sm-4">
				<div class=" {{ $value==$icon? $selected_class : $unselected_class }} m5 bg-{{$icon}} clickable iconSelector " style="width: 40px;height: 40px;"
					 onclick="$('.iconSelector').removeClass('{{ $selected_class }}').addClass('{{ $unselected_class }}');$(this).removeClass('{{ $unselected_class }}').addClass('{{ $selected_class }}');$('{{"#txt_$name"}}').val('{{$icon}}')"
				>
					<i class="fa fa-{{$icon}} p10"></i>
				</div>
			</div>
		@endforeach
	</div>

	@include("forms.hidden" , [
		'id' => "txt_$name",
	])

	@include("forms.group-end" , [
		'' => "",
	])


@endif