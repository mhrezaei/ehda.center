@php
	if(!isset($states)) {
		$states = model('state')::combo();
	}
@endphp


@include('forms.opener' , [
	'id' => 'frmEditor',
	'url' => 'manage/volunteers/save',
	'title' => $model->id? trans('forms.button.edit_info').' '.$model->full_name : trans('ehda.volunteers.create') ,
	'class' => 'js' ,
	'no_validation' => 1 ,
])

@include('forms.hiddens' , ['fields' => [
	['id' , encrypt($model->id)],
	['code_melli' , $model->code_melli],
	['status', '0'],
	['role_slug' , 'manager'],
]])

@include('forms.input' , [
	'name' => 'code_melli',
	'value' =>  $model->code_melli ,
	'extra' => 'disabled' ,
])

@if(!$model->id or !$model->withDisabled()->is_admin())
	@php
		$array = user()->userRolesArray('create' , array_add( $model->withDisabled()->rolesArray()  , 'n' , 'card-holder') ) ;
		$combo = model('role')::whereIn('slug' , $array)->orderBy('title') ;
		$role_model = model('role')::whereIn('slug' , $array)->first();
		if(isset($option) and $option and $option != 'admin') {
			$default_role = $option ;
		}
		elseif(isset($request_role) and $request_role and $request_role != 'admin') {
			$default_role = $request_role ;
		}
		elseif($combo->count()==1) {
			$default_role = $role_model->slug ;
		}
		else {
			$default_role = null;
		}
	@endphp

	@include("forms.select" , [
		'name' => "role_slug",
		'blank_value' => "" ,
		'value' => $default_role ,
		'options' => $combo->get() ,
		'search' => true ,
		'value_field' => "slug" ,
		'class' => "form-required" ,
	])

	@include("forms.select" , [
		'name' => "status" ,
		'options' => $role_model->statusCombo() ,
		'caption_field' => "1" ,
		'value_field' => "0" ,
		'class' => "form-required" ,
		'blank_value' => "" ,
	]     )
@endif

@include('forms.select-gender' , [
	'value' => $model->id? $model->gender : '0' ,
	'blank_value' => $model->id? 'NO' : ' ',
	'class' => 'form-required form-default',
])

@include('forms.input' , [
	'name' => 'name_first',
	'value' =>$model->name_first ,
	'class' => 'form-required'
])

@include('forms.input' , [
'name' => 'name_last',
'value' =>$model->name_last ,
'class' => 'form-required'
])

@include('forms.input' , [
	'name' => 'name_father',
	'value' => $model->name_father ,
	'class' => '' ,
])

@include('forms.input' , [
	'name' => 'code_id',
	'value' => $model->code_id ,
	'class' => 'form-number' ,
])

@include('forms.sep')

@include('forms.select' , [
	'name' => 'birth_city' ,
	'class' => '',
	'value' => $model->id? $model->birth_city : '0' ,
	'blank_value' => '' ,
	'options' => $states ,
	'search' => true ,
	'search_placeholder' => trans('forms.button.search') ,
])

@include('forms.datepicker' , [
	'name' => 'birth_date',
	'class' => 'form-required' ,
	'value' => $model->birth_date ,
])

@include('forms.sep')

@include('forms.input' , [
		'name' => 'job',
		'value' => $model->job  ,
	])

@include('forms.select-education' , [
	'name' => 'edu_level' ,
	'class' => '' ,
	'blank_value' => '' ,
	'value' => $model->edu_level ,
])

@include('forms.sep')

@include('forms.input' , [
'name' => 'home_tel',
'value' => $model->home_tel ,
'class' => 'ltr',
])

@include('forms.input' , [
	'name' => 'mobile',
	'value' => $model->mobile ,
	'class' => 'ltr form-required',
])

@include('forms.input' , [
	'name' => 'tel_emergency',
	'value' => $model->tel_emergency ,
	'class' => 'form-required ltr',
])

@include('forms.select' , [
	'name' => 'home_city' ,
	'value' => $model->id? $model->home_city : '0' ,
	'blank_value' => '' ,
	'options' => $states ,
	'search' => true ,
	'class' => 'form-required'
])

@include('forms.sep')

@include('forms.input' , [
	'name' => 'email',
	'value' => $model->email ,
	'class' => 'ltr',
	'type' => 'email'
])

{{--@if(!$model->id)--}}
{{--@include('forms.input' , [--}}
{{--'name' => 'password',--}}
{{--'value' => rand(10000000 , 99999999),--}}
{{--'class' => 'form-required ltr'--}}
{{--])--}}
{{--@endif--}}

@include('forms.sep')

@include('forms.select-marital' , [
	'blank_value' => ' ',
	'value' => $model->id? $model->marital : '0' ,
	'class' => '',
])

@include('forms.sep')

@include('forms.input' , [
	'name' => 'edu_field',
	'value' => $model->edu_field,
	'class' => '' ,
])

@include('forms.select' , [
	'name' => 'edu_city' ,
	'value' =>  $model->edu_city  ,
	'blank_value' => '0' ,
	'options' => $states ,
	'search' => true ,
	'search_placeholder' => trans('forms.button.search') ,
])

@include('forms.sep')

@include('forms.input' , [
	'name' => 'home_address',
	'value' => $model->home_address,
])

@include('forms.input' , [
	'name' => 'home_postal',
	'value' => $model->home_postal  ,
	'class' => 'ltr',
])


@include('forms.sep')

@include('forms.select' , [
	'name' => 'work_city' ,
	'value' => $model->work_city ,
	'blank_value' => '0' ,
	'options' => $states ,
	'search' => true ,
])

@include('forms.input' , [
	'name' => 'work_address',
	'value' => $model->work_address ,
])

@include('forms.input' , [
	'name' => 'work_tel',
	'value' => $model->work_tel ,
	'class' => 'ltr',
])

@include('forms.input' , [
	'name' => 'work_postal',
	'value' =>  $model->work_postal,
	'class' => 'ltr',
])

@include('forms.sep')

@include('forms.select-familiarization' , [
	'class' => '' ,
	'value' => $model->familiarization+0 ,
])

@include('forms.input' , [
	'name' => 'motivation',
	'value' => $model->motivation ,
])

@include('forms.input' , [
	'name' => 'alloc_time',
	'value' => $model->alloc_time ,
])


{{--
|--------------------------------------------------------------------------
| Activities
|--------------------------------------------------------------------------
|
--}}
@include("forms.sep")
@include("forms.group-start" , [
	'label' => trans("validation.attributes.activities") ,
])



@foreach(model('activity')::sortedAll() as $activity)

	@include("forms.check" , [
		'name' => '_activity-'.$activity->slug,
		'value' => in_array($activity->slug , $model->activities_array) ,
		'label' => $activity->title ,
	]     )


@endforeach



@include("forms.group-end")




{{--
|--------------------------------------------------------------------------
| Password
|--------------------------------------------------------------------------
|
--}}


@include('forms.sep')


@include('forms.group-start' , [
	'label' => trans('validation.attributes.password')
])

@if($model->id)
	@include('forms.check' , [
		'name' => '_password_set_to_mobile',
		'value' => false ,
		'label' => trans('people.form.password_set_to_mobile') ,
	])

@else
	<div class="text-danger disabled mv5">
		<i class="fa fa-check-square"></i>
		{{ trans('people.form.default_password') }}
	</div>
@endif


@include('forms.group-end')








{{--
|--------------------------------------------------------------------------
| Buttons
|--------------------------------------------------------------------------
|
--}}


@include('forms.sep')

@include('forms.group-start')

@include('forms.button' , [
	'label' => trans('forms.button.save'),
	'shape' => 'success',
	'type' => 'submit' ,
])

@include('forms.group-end')

@include('forms.feed' , [])

@include('forms.closer')
