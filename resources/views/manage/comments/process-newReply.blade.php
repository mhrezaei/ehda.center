@include("forms.hiddens" , [ "fields" => [
	['id' , $model->id],
]])

@include("forms.textarea" , [
	'name' => "reply",
	'class' => "form-autoSize form-default" ,
	'id' => "txtReply" ,
//	'value' => $reply = $model->children()->first() ? $reply->text : '' ,
]     )
@include("forms.select" , [
	'name' => "status",
	'value' => $model->status ,
	'options' => $model->statusCombo() ,
	'value_field' => "0" ,
	'caption_field' => "1" ,
]     )

@include("forms.check-form" , [
	'name' => "send_email",
	'value' => 1 ,
	'condition' => $model->email or $model->user_id ,
	'self_label' => trans('posts.comments.reply_via_email_too') ,
]     )

@include('forms.group-start')

@include('forms.button' , [
	'label' => trans('forms.button.save'),
	'shape' => 'success',
	'type' => 'submit' ,
	'value' => 'save' ,
	'class' => '-delHandle',
])

@include('forms.button' , [
	'label' =>  trans('forms.button.cancel') ,
	'shape' => 'link' ,
	'link' => '$(".modal").modal("hide")',
])

@include('forms.group-end')

@include('forms.feed')
