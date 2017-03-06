@if(!isset($condition) or $condition)

	@include("forms.group-start" , [
		'fake' => !isset($name)? $name = 'color_code' : '',
		'fake2' => !isset($value)? $value = '' : '',
		'label' => isset($label)? $label : trans("validation.attributes.$name"),
	])

	<div class="row">
		@foreach($colors as $color)
			<div class="col-lg-1 col-md-2 col-sm-4">
				<div class=" {{ $value==$color? 'img-rounded' : 'img-circle' }} m5 bg-{{$color}} clickable colorCode" style="width: 30px;height: 30px;border: 1px solid black"
					 onclick="$('.colorCode').removeClass('img-rounded').addClass('img-circle');$(this).removeClass('img-circle').addClass('img-rounded');$('{{"#txt_$name"}}').val('{{$color}}')"
				>
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