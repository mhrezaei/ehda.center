@if(!isset($condition) or $condition)
	@include('forms.group-start')

	@include('forms.check' , [
		'label' => $self_label
	])

	@include('forms.group-end')

@endif