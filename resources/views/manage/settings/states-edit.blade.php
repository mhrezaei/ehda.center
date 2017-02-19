@include('templates.modal.start' , [
	'form_url' => url('manage/upstream/save/state'),
	'modal_title' => $model->id? trans('settings.province_edit') : trans('settings.province_new'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id],
		['parent_id' , '0']
	]])


	@include('forms.input' , [
		'name' =>	'title',
		'class' => 'form-required form-default' ,
		'value' => $model->title ,
		'hint' =>	trans('validation.hint.unique').' | '.trans('validation.hint.persian-only'),
	])

	@include('forms.select' , [
		'name' =>	'capital_id',
	//	'class' => 'form-required',
		'options' => $model->cities()->orderBy('title')->get()->toArray() ,
		'value' => $model->id? $model->capital()->id : '0' ,
		'blank_value' => '0' ,
		'search' => true ,
	])

	@if($model->id)
		@include('forms.note' , [
			'fake' => $city_count = $model->cities()->count() ,
			'shape' => $city_count? 'warning' : 'danger' ,
			'text' => $city_count? trans('settings.province_cant_delete_alert' , ['count' => $city_count]) : trans('settings.city_delete_alert') ,
			'class' => '-delHandle noDisplay'
		])
	@endif


	@include('forms.group-start')

		@include('forms.button' , [
			'id' => 'btnSave' ,
			'label' => trans('forms.button.save'),
			'shape' => 'success',
			'type' => 'submit' ,
			'value' => 'save' ,
			'class' => '-delHandle'
		])

		@if($model->id)
			@include('forms.button' , [
				'id' => 'btnDeleteWarning' ,
				'label' => trans('forms.button.delete'),
				'shape' => 'warning',
				'link' => '$(".-delHandle").toggle()' ,
				'class' => '-delHandle' ,
			])
			@include('forms.button' , [
				'id' => 'btnDelete' ,
				'label' => trans('forms.button.sure_hard_delete'),
				'shape' => 'danger',
				'value' => 'delete' ,
				'type' => 'submit' ,
				'class' => 'noDisplay -delHandle' ,
				'extra' => $city_count? 'disabled' : ''
			])

		@endif


		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")',
		])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')