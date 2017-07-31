@include('forms.opener' , [
	'url' => 'manage/account/save/profile',
	'class' => 'js mv20' ,
	'no_validation' => 1
])

	{{--
	|--------------------------------------------------------------------------
	| Notes
	|--------------------------------------------------------------------------
	|
	--}}
	@if(!$model->min(8)->is_admin())
		@include("forms.note" , [
			'text' => trans('settings.account.profile_completions_note_for_new_volunteers'),
			'shape' => "info" ,
		]     )
	@elseif($model->unverified_flag < 0)
		@include('forms.note' , [
			'text' => trans('settings.account.profile_reject_note') ,
			'shape' => 'danger' ,
		])
		@if($edit_reject_notice = $model->edit_reject_notice)
			@include('forms.note' , [
				'text' => trans('validation.attributes.reject_reason').": $edit_reject_notice" ,
				'shape' => 'warning' ,
			])
		@endif
	@elseif($model->unverified_flag > 0)
		@include('forms.note' , [
			'text' => trans('settings.account.profile_pending_note')
		])
	@endif


	{{--
	|--------------------------------------------------------------------------
	| Main Form
	|--------------------------------------------------------------------------
	|
	--}}
	@include("manage.account.profile-form-inside" , [
		'show_unchanged' => true,
	]     )

	{{--
	|--------------------------------------------------------------------------
	| Form Buttons
	|--------------------------------------------------------------------------
	|
	--}}
	@include('forms.group-start')

		@include('forms.button' , [
			'label' => !$model->min(8)->is_admin()? trans('forms.button.save') : trans('settings.account.profile_save'),
			'shape' => 'primary',
			'type' => 'submit' ,
			'value' => 'save',
		])

		@if($model->min(8)->is_admin())
			@include('forms.button' , [
				'label' => trans('settings.account.profile_revert'),
				'shape' => 'danger',
				'type' => 'submit' ,
				'value' => 'revert',
			])
		@endif

	@include('forms.group-end')

	@include('forms.feed' , [])



@include("forms.closer")