@include('forms.select' , [
	'name' => isset($name)? $name : 'familiarization' ,
	'value' => isset($value)? $value : '0' ,
	'class' => isset($class)? $class : '' ,
	'blank_value' => isset($blank_value)? $blank_value : '0',
	'size' => 10 ,

	'options' => [
		['id'=>'1' , 'title'=>trans('people.familiarization.1')],
		['id'=>'2' , 'title'=>trans('people.familiarization.2')],
		['id'=>'3' , 'title'=>trans('people.familiarization.3')],
		['id'=>'4' , 'title'=>trans('people.familiarization.4')],
	]
])