{{--
|--------------------------------------------------------------------------
| General
|--------------------------------------------------------------------------
| 
--}}
@include("forms.hiddens" , ['fields' => [
	['id' , $model->id] ,
	['type' , $model->encrypted_type],
]])


{{--
|--------------------------------------------------------------------------
| Moderate and reject buttons
|--------------------------------------------------------------------------
| 
--}}

@include("forms.textarea" , [
	'name' => "moderate_note",
	'id' => "txtModerateNote",
	'in_form' => false,
])
@include("forms.button" , [
	'name' => "_submit",
	'type' => "submit",
	'value' => "reject",
	'id' => "btnReject",
	'label' => "reject",
])
