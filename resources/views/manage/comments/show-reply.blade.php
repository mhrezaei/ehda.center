
@include("forms.hiddens" , [ "fields" => [
	['id' , $parent->id],
]])

@include("forms.textarea" , [
    'name' => "reply",
    'class' => "form-autoSize form-default" ,
    'id' => "txtReply" ,
    'rows' => "3" ,
    'condition' => !$relatedPost->unanswerable
])

@include("forms.select" , [
	'name' => "status",
	'value' => $parent->status ,
	'options' => $parent->statusCombo() ,
	'value_field' => "0" ,
	'caption_field' => "1" ,
	'condition' => $parent->children()->count()==0,
]     )

@include("forms.check-form" , [
    'name' => "send_email",
    'value' => 1 ,
    'condition' => ($parent->email or $parent->user_id) and !$relatedPost->unanswerable,
    'self_label' => trans('posts.comments.reply_via_email_too') ,
])

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

@include('forms.button' , [
    'condition' => (
        ($targetPosttype = PostsServiceProvider::smartFindPosttype($relatedPost->target_post_type))->exists and
        ($parent->status === 'pending')
    ),
	'label' => trans('forms.button.create_in', ['thing' => $targetPosttype->titleIn(getLocale())]),
	'shape' => 'primary',
	'link' => route('manage.comments.convert-to-post', ['model_id' => $parent->hashid]),
])

@include('forms.group-end')

@include('forms.feed')
