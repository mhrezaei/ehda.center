@include('forms.input' , [
	'name' => '_name',
	'label' => trans('validation.attributes.code_melli'),
	'value' => $model->code_melli ,
	'extra' => 'disabled' ,
])

@if($show_unchanged or isset($model->changes->name_first))
	@include('forms.input' , [
		'name' => 'name_first',
		'value' => $model->name_first ,
		'class' => 'form-required form-default'
	])
@endif

@if($show_unchanged or isset($model->changes->name_last))
	@include('forms.input' , [
	'name' => 'name_last',
	'value' => $model->name_last ,
	'class' => 'form-required ' ,
	])
@endif


@if($show_unchanged or isset($model->changes->code_id))
	@include('forms.input' , [
		'name' => 'code_id',
		'value' => $model->code_id ,
		'class' => 'form-number' ,
	])
@endif



@if($show_unchanged or isset($model->changes->name_father))
	@include('forms.input' , [
		'name' => 'name_father',
		'value' => $model->name_father ,
		'class' => 'form-required' ,
	])
@endif

@if($show_unchanged or isset($model->changes->gender))
	@include('forms.select-gender' , [
		'value' => $model->gender,
		'blank_value' => $model->gender? 'NO' : ' ',
		'class' => 'form-required',
	])
@endif

@if($show_unchanged or isset($model->changes->marital))
	@include('forms.select-marital' , [
		'blank_value' => ' ',
		'value' => isset($model->changes->marital)? $model->changes->marital : $model->marital ,
		'class' => 'form-required',
		'hint' => isset($model->changes->marital)? trans('settings.account.in_profile' , ['v'=>$model->marital_name ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->email))
	@include('forms.input' , [
		'name' => 'email',
		'class' => 'form-required ltr',
		'type' => 'email' ,
		'value' => isset($model->changes->email)? $model->changes->email : $model->email ,
		'hint' => isset($model->changes->email)? trans('settings.account.in_profile' , ['v'=>$model->email ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif


@if($show_unchanged or isset($model->changes->mobile))
	@include('forms.input' , [
		'name' => 'mobile',
		'class' => 'form-required ltr',
		'value' => isset($model->changes->mobile)? $model->changes->mobile : $model->mobile ,
		'hint' => isset($model->changes->mobile)? trans('settings.account.in_profile' , ['v'=>pd($model->mobile) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->tel_emergency))
	@include('forms.input' , [
		'name' => 'tel_emergency',
		'class' => 'form-required ltr',
		'value' => isset($model->changes->tel_emergency)? $model->changes->tel_emergency : $model->tel_emergency ,
		'hint' => isset($model->changes->tel_emergency)? trans('settings.account.in_profile' , ['v'=>pd($model->tel_emergency) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged)
	@include('forms.sep')
@endif

@if($show_unchanged or isset($model->changes->edu_level))
	@include('forms.select-education' , [
		'name' => 'edu_level' ,
		'value' => isset($model->changes->edu_level)? $model->changes->edu_level : $model->edu_level ,
		'hint' => isset($model->changes->edu_level)? trans('settings.account.in_profile' , ['v'=>$model->edu_level_name ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->edu_field))
	@include('forms.input' , [
		'name' => 'edu_field',
		'value' => isset($model->changes->edu_field)? $model->changes->edu_field : $model->edu_field ,
		'hint' => isset($model->changes->edu_field)? trans('settings.account.in_profile' , ['v'=>$model->edu_field ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->edu_city))
	@include('forms.select' , [
		'name' => 'edu_city' ,
		'blank_value' => '0' ,
		'options' => $states ,
		'search' => true ,
		'search_placeholder' => trans('forms.button.search') ,
		'value' => isset($model->changes->edu_city)? $model->changes->edu_city : $model->edu_city ,
		'hint' => isset($model->changes->edu_city)? trans('settings.account.in_profile' , ['v'=>$model->edu_city_name ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged)
	@include('forms.sep')
@endif

@if($show_unchanged or isset($model->changes->birth_city))
	@include('forms.select' , [
		'name' => 'birth_city' ,
		'class' => 'form-required',
		'value' => $model->birth_city ,
		'blank_value' => '0' ,
		'options' => $states ,
		'search' => true ,
		'search_placeholder' => trans('forms.button.search') ,
	])
@endif

@if($show_unchanged or isset($model->changes->birth_date))
	@include('forms.datepicker' , [
		'name' => 'birth_date',
		'value' => $model->birth_date ,
	])
@endif

@if($show_unchanged or isset($model->changes->home_city))
	@include('forms.select' , [
		'name' => 'home_city' ,
		'blank_value' => '0' ,
		'options' => $states ,
		'search' => true ,
		'value' => isset($model->changes->home_city)? $model->changes->home_city : $model->home_city ,
		'hint' => isset($model->changes->home_city)? trans('settings.account.in_profile' , ['v'=>$model->home_city_name ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->home_address))
	@include('forms.input' , [
		'name' => 'home_address',
		'value' => isset($model->changes->home_address)? $model->changes->home_address : $model->home_address ,
		'hint' => isset($model->changes->home_address)? trans('settings.account.in_profile' , ['v'=>pd($model->home_address) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->home_tel))
	@include('forms.input' , [
		'name' => 'home_tel',
		'class' => 'ltr',
		'value' => isset($model->changes->home_tel)? $model->changes->home_tel : $model->home_tel ,
		'hint' => isset($model->changes->home_tel)? trans('settings.account.in_profile' , ['v'=>pd($model->home_tel) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->home_postal))
	@include('forms.input' , [
		'name' => 'home_postal',
		'class' => 'ltr',
		'value' => isset($model->changes->home_postal)? $model->changes->home_postal : $model->home_postal ,
		'hint' => isset($model->changes->home_postal)? trans('settings.account.in_profile' , ['v'=>pd($model->home_postal) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged)
	@include('forms.sep')
@endif

@if($show_unchanged or isset($model->changes->job))
	@include('forms.input' , [
		'name' => 'job',
		'value' => isset($model->changes->job)? $model->changes->job : $model->job ,
		'hint' => isset($model->changes->job)? trans('settings.account.in_profile' , ['v'=>$model->job ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif


@if($show_unchanged or isset($model->changes->work_city))
	@include('forms.select' , [
		'name' => 'work_city' ,
		'blank_value' => '0' ,
		'options' => $states ,
		'search' => true ,
		'value' => isset($model->changes->work_city)? $model->changes->work_city : $model->work_city ,
		'hint' => isset($model->changes->work_city)? trans('settings.account.in_profile' , ['v'=>$model->work_city_name ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->work_address))
	@include('forms.input' , [
		'name' => 'work_address',
		'value' => isset($model->changes->work_address)? $model->changes->work_address : $model->work_address ,
		'hint' => isset($model->changes->work_address)? trans('settings.account.in_profile' , ['v'=>$model->work_address ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->work_tel))
	@include('forms.input' , [
		'name' => 'work_tel',
		'class' => 'ltr',
		'value' => isset($model->changes->work_tel)? $model->changes->work_tel : $model->work_tel ,
		'hint' => isset($model->changes->work_tel)? trans('settings.account.in_profile' , ['v'=>pd($model->work_tel) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->work_postal))
	@include('forms.input' , [
		'name' => 'work_postal',
		'class' => 'ltr',
		'value' => isset($model->changes->work_postal)? $model->changes->work_postal : $model->work_postal ,
		'hint' => isset($model->changes->work_postal)? trans('settings.account.in_profile' , ['v'=>pd($model->work_postal) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])

@endif

@if($show_unchanged)
	@include('forms.sep')
@endif

@if($show_unchanged or isset($model->changes->familiarization))
	@include('forms.select-familiarization' , [
			'class' => '' ,
			'value' => isset($model->changes->familiarization)? $model->changes->familiarization : $model->familiarization ,
			'hint' => isset($model->changes->familiarization)? trans('settings.account.in_profile' , ['v'=>trans("people.familiarization.$model->familiarization") ]) : '' ,
			'hint_class' => 'help-flag' ,
		])
@endif

@if($show_unchanged or isset($model->changes->motivation))
	@include('forms.input' , [
		'name' => 'motivation',
		'value' => isset($model->changes->motivation)? $model->changes->motivation : $model->motivation ,
		'hint' => isset($model->changes->motivation)? trans('settings.account.in_profile' , ['v'=>$model->motivation ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif

@if($show_unchanged or isset($model->changes->alloc_time))
	@include('forms.input' , [
		'name' => 'alloc_time',
		'value' => isset($model->changes->alloc_time)? $model->changes->alloc_time : $model->alloc_time ,
		'hint' => isset($model->changes->alloc_time)? trans('settings.account.in_profile' , ['v'=>pd($model->alloc_time) ]) : '' ,
		'hint_class' => 'help-flag' ,
	])
@endif


{{--
|--------------------------------------------------------------------------
| Activities
|--------------------------------------------------------------------------
|
--}}
@include("forms.sep")
@if($show_unchanged or isset($model->changes->activities))

	@include("forms.group-start" , [
		'label' => trans("validation.attributes.activities") ,
	])
		@foreach(model('activity')::sortedAll() as $activity)
			{{ '' , $real_value = in_array($activity->slug , $model->activities_array) }}
			{{ '' , $changed_value = in_array($activity->slug , $model->changed_activities_array) }}

			@if($real_value != $changed_value or $show_unchanged)

				@include("forms.check" , [
					'name' => '_activity-'.$activity->slug,
					'value' => isset($model->changes->activities)? $changed_value : $real_value,
					'label' => $activity->title ,
				]     )

				@if($real_value != $changed_value and isset($model->changes->activities))
					<div class="f10 text-danger mv5 mh20">
						@if($real_value)
							{{ trans("people.form.exist_in_profile") }}
						@else
							{{ trans("people.form.dont_exist_in_profile") }}
						@endif
					</div>
				@endif
			@endif

		@endforeach

	@include("forms.group-end")

@endif
