@include('templates.modal.start' , [
	'form_url' => url('manage/account/save/card-register'),
	'modal_title' => trans('ehda.cards.register_full'),
])
@if(user()->is_not_a('card-holder'))
	<div class='modal-body'>

		@include("forms.input" , [
			'name' => "",
			'label' => trans("validation.attributes.name_first") ,
			'value' => user()->full_name ,
			'extra' => "disabled" ,
		]     )

		@include("forms.input" , [
			'name' => "",
			'label' => trans("validation.attributes.code_melli") ,
			'value' => pd(user()->code_melli) ,
			'extra' => "disabled" ,
		]     )


		@include("forms.group-start")
			@include("manage.frame.widgets.grid-text" , [
				'icon' => "check-square-o",
				'text' => trans("ehda.cards.agreement_note_line_1") ,
				'color' => "success" ,
			]     )
			@include("manage.frame.widgets.grid-text" , [
				'text' => trans("ehda.cards.agreement_note_line_2") ,
				'color' => "success" ,
			]     )
		@include('forms.group-end')

		@include('forms.group-start')

			@include('forms.button' , [
				'label' => trans('ehda.cards.register_full'),
				'shape' => 'success',
				'type' => 'submit' ,
			])
			@include('forms.button' , [
				'label' => trans('forms.button.cancel'),
				'shape' => 'link',
				'link' => '$(".modal").modal("hide")',
			])

		@include('forms.group-end')

		@include('forms.feed')
	</div>
@endif




@if(user()->is_a('card-holder'))
	<div class='modal-body'>
		<div class="mv20 f20 text-center">
			{{ trans("ehda.cards.you_already_have") }}
		</div>
	</div>
@endif

@include('templates.modal.end')