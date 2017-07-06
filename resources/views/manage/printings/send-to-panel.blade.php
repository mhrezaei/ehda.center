@include('forms.opener' , [
	'url' => 'manage/cards/save/add_to_print',
	'class' => 'js' ,
	'no_validation' => 1 ,
])

@include("forms.hidden" , [
	'name' => "user_id" ,
	'value' => $user_id,
]     )

	<div id="{{ $id or 'divSendToPanel' }}" class="panel panel-default {{ (!isset($display) or !$display)? 'noDisplay' : '' }} mv10" style="text-align: right">
		<div class="panel-heading">{{ trans("ehda.printings.send_to") }}</div>
		<div class="panel-body row">
			<div class="col-md-12 mv5">
				@include("forms.feed")
			</div>

			<div class="col-md-8">
				@include("forms.select_self" , [
					'id' => "cmbSendToPrinting",
					'name' => "event_if_for_print" ,
					'options' => model('post')::activeEventsArray() ,
					'value' => session()->get('user_last_used_event') ,
				]     )
			</div>


			<div class="col-md-4">
				<button class="btn btn-primary w100" type="submit">{{ trans("ehda.printings.send_to")  }}</button>
			</div>



		</div>
	</div>

@include("forms.closer")