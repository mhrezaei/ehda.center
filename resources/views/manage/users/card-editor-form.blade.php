@include('forms.opener' , [
	'id' => 'frmEditor',
	'url' => 'manage/cards/save',
	'title' => $model->is_a('card-holder')? trans('ehda.cards.edit').' '.$model->full_name : trans('ehda.cards.create') ,
	'class' => 'js' ,
	'no_validation' => 1 ,
])

@include('forms.hiddens' , ['fields' => [
	['id' , encrypt($model->id)],
	['code_melli' , $model->code_melli]
]])

@include('forms.input' , [
	'name' => 'code_melli',
	'value' =>  $model->code_melli ,
//		'class' => 'disabled',
	'extra' => 'disabled' , //Auth::user()->isDeveloper()? '' : 'disabled' ,
])

@if(!$model->id)
	@include("forms.select" , [
		'name' => "from_event_id",
		'value' => $model->from_event_id,
		'blank_value' => "",
		'blank_label' => " ",
		'required' => true ,
		'options' => $events,
	])
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

@include('forms.sep')

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

@include('forms.group-start' , [
	'label' => trans('validation.attributes.newsletter')
])

@include('forms.check' , [
	'name' => 'newsletter',
	'value' => $model->newsletter,
	'label' => trans('ehda.newsletter_membership')
])

@include('forms.group-end')

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

@if($model->id)
	@include("forms.select" , [
		'name' => "from_event_id",
		'value' => $model->from_event_id,
		'blank_value' => "",
		'blank_label' => " ",
		'options' => $events,
		'hint' => trans('ehda.cards.event_hint_for_card_edits'),
	])


@endif

@include('forms.sep')

@include('forms.group-start')

@include('forms.button' , [
	'label' => trans('forms.button.save'),
	'shape' => 'success',
	'type' => 'submit' ,
])

@include('forms.button' , [
	'label' => trans('ehda.cards.save_and_send_to_print'),
	'value' => 'print' ,
	'shape' => 'primary',
	'type' => 'submit' ,
])

@include('forms.group-end')

@include('forms.feed' , [])

@include('forms.closer')
