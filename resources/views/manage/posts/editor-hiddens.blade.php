{{--
|--------------------------------------------------------------------------
| General
|--------------------------------------------------------------------------
| 
--}}
@include("forms.hiddens" , ['fields' => [
	['id' , $model->id , 'txtId'] ,
	['type' , $model->type , 'txtType'],
	['sisterhood' , $model->sisterhood]
]])


{{--
|--------------------------------------------------------------------------
| Hidden "approval" button
|--------------------------------------------------------------------------
|
--}}
@include("forms.button" , [
	'name' => "_submit",
	'type' => "submit",
	'value' => "approval",
	'id' => "btnApproval",
	'label' => "Approval",
])

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
