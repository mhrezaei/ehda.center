<div class="mv10 f20">{{ $model->full_name }}</div>

@include("forms.input" , [
	'name' => "",
	'label' => trans("people.user_role") ,
	'value' => $request_role->title  ,
	'disabled' => "1" ,
]     )

