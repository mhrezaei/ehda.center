@include('forms.opener' , [
	'url' => 'manage/cards/save/delete',
	'class' => 'js' ,
	'no_validation' => 1 ,
])

@include("forms.hidden" , [
	'name' => "user_id" ,
	'value' => $user_id,
]     )

<div id="{{ $id or 'divCardDelete' }}" class="panel panel-danger {{ (!isset($display) or !$display)? 'noDisplay' : '' }} mv10" style="text-align: right">
	<div class="panel-heading">{{ trans("ehda.cards.delete") }}</div>
	<div class="panel-body row">
		<div class="col-md-12 mv5">
			@include("forms.feed")
		</div>

		<div class="col-md-8 p5 ph15 text-danger">
			{{ trans("ehda.cards.delete_warning_in_manage") }}
		</div>


		<div class="col-md-4">
			<button class="btn btn-danger w100" type="submit">{{ trans("ehda.cards.delete")  }}</button>
		</div>



	</div>
</div>

@include("forms.closer")