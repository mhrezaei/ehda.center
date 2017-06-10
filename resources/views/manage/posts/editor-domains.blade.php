@if($model->has('domains'))

	@include("forms.check" , [
		'name' => "disable_receiving_comments",
		'value' => $model->disable_receiving_comments ,
	])

	@include("forms.check" , [
		'name' => "disable_showing_comments",
		'value' => $model->disable_showing_comments ,
	])

@endif