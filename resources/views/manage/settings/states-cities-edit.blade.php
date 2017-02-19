@include('templates.modal.start' , [
	'partial' => true ,
	'form_url' => url('manage/upstream/save/city'),
	'modal_title' => $model->id? trans('settings.city_edit') : trans('settings.city_new'),
])
	<div class='modal-body'>

		@include('forms.hidden' , [
			'name' => 'id' ,
			'value' => $model->id ,
		])

		@include('forms.input' , [
			'name' =>	'title',
			'class' => 'form-required form-default' ,
			'value' => $model->title ,
		])

		@include('forms.select' , [
			'name' =>	'province_id',
			'class' => 'form-required',
			'options' => $provinces->toArray(),
			'value' => $model->parent_id  ,
			'blank_value' => '0' ,
		])

		@include('forms.note' , [
			'shape' => 'danger' ,
			'text' => trans('settings.city_delete_alert') ,
			'class' => '-delHandle noDisplay'
		])

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
					'class' => 'noDisplay -delHandle'
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