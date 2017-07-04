@include('forms.select' , [
	'name' => isset($name)? $name : 'education' ,
	'value' => isset($value)? $value : '0' ,
	'class' => isset($class)? $class : '' ,
	'blank_value' => isset($blank_value)? $blank_value : '0',
	'size' => 10 ,

	'options' => [
		['id'=>'1' , 'title'=>trans('people.edu_level_full.1')],
		['id'=>'2' , 'title'=>trans('people.edu_level_full.2')],
		['id'=>'3' , 'title'=>trans('people.edu_level_full.3')],
		['id'=>'4' , 'title'=>trans('people.edu_level_full.4')],
		['id'=>'5' , 'title'=>trans('people.edu_level_full.5')],
		['id'=>'6' , 'title'=>trans('people.edu_level_full.6')],
	]
])