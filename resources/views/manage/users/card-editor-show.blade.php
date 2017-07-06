<div class="row">
	<div class="col-md-6">
		<img src="{{ $model->cards('social' , 'show') }}" style="width: 450px">
	</div>
	<div class="col-md-6 p40">

		<div>
			@include("forms.button" , [
				'label' => trans("forms.button.edit"),
				'shape' => "default" ,
				'class' => "btn-lg m30 w50" ,
				'condition' => $model->canEdit() ,
				'link' => url("manage/cards/edit/$model->hash_id") ,
			]     )
		</div>
		
		<div>
			@include("forms.button" , [
				'label' => trans("ehda.cards.send_to_print_quee"),
				'shape' => "default" ,
				'class' => "btn-lg w50" ,
				'link' => "$('#divSendToPanel').slideToggle('fast')" ,
			]     )
		</div>

	</div>
	<div class="col-md-12">
		@include("manage.printings.send-to-panel" , [
			'user_id' => $model->id ,
		]     )
	</div>
</div>

