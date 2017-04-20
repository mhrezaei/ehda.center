<tr>
	<td style="vertical-align: middle"><span class="fa fa-plus"></span></td>
	<td colspan="1">
		@include("forms.hidden" , [
			'name' => "post_id",
			'value' => $model->id,
		])
		@include("forms.input-self" , [
			'id' => "txtDrawingGuess",
			'name' => "random_number",
			'placeholder' => trans('cart.take_number_between' , ['number' => pd($max_possible_number = session()->get('line_number')),]),
			'class' => "text-center ltr",
		])
	</td>
	<td colspan="3">
		@include("forms.button" , [
			'id' => "btnSubmit",
			'label' => trans('forms.general.submit'),
			'type' => "submit",
		])
		@include("forms.button" , [
			'label' => trans('cart.random_number'),
			'type' => "button",
			'link' => "drawingRandom($max_possible_number)",
			'shape' => "link",
		])
	</td>
</tr>