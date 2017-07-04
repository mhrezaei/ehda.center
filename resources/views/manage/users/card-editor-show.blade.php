<div class="row">
	<div class="col-md-6">
		<img src="{{url("/card/show_card/single/$model->hash_id")}}" style="width: 450px">
	</div>
	<div class="col-md-6 p40">

		<div>
			@include("forms.button" , [
				'label' => trans("forms.button.edit"),
				'shape' => "default" ,
				'class' => "btn-lg m30 w50" ,
				'condition' => $model->canEdit() ,
			]     )
		</div>
		
		<div>
			@include("forms.button" , [
				'label' => trans("ehda.cards.send_to_print_quee"),
				'shape' => "default" ,
				'class' => "btn-lg w50" ,
			]     )
		</div>

	</div>
</div>

