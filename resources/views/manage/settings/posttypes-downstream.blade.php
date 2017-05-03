@include("templates.modal.start" , [
	'partial' => "true",
	'form_url' => "manage/settings/save/posttype" ,
	'modal_title' => trans('settings.downstream').' '.$model->title ,
]     )

<div class="modal-body">

	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	|
	--}}

	@include("forms.hiddens" , [ "fields" => [
		['id' , $model->id],
	]])

	{{--
	|--------------------------------------------------------------------------
	| Body
	|--------------------------------------------------------------------------
	|
	--}}
	@foreach($model->downstream() as $item )
		@include("manage.frame.widgets.input-".$item['type'] , [
			'name' => $item['name'],
			'class' => str_contains($item['rules'],'required')? 'form-required' : '' ,
			'value' => isset($model->toArray()[$item['name']]) ? $model->toArray()[$item['name']] : '' ,
			'hint' => Lang::has("validation.attributes_placeholder.".$item['name']) ? trans("validation.attributes_placeholder.".$item['name']) : '' ,
		]     )
	@endforeach

	{{--
	|--------------------------------------------------------------------------
	| Buttons and Footer
	|--------------------------------------------------------------------------
	|
	--}}
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

		@include("forms.button" , [
			'condition' => user()->is_a('developer') ,
			'label' => trans('settings.upstream'),
			'shape' => "link" ,
			'link' => "masterModal(url('manage/upstream/edit/posttype/$model->id'))" ,
		]     )
	@include('forms.group-end')

	@include('forms.feed')

	@include('forms.group-end')

</div>