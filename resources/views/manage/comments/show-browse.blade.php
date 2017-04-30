{{--
|--------------------------------------------------------------------------
| Parent
|--------------------------------------------------------------------------
|
--}}
@include("manage.comments.show-one" , [
	'comment' => $parent = $model->parent(),
	'parent' => true ,
]     )

{{--
|--------------------------------------------------------------------------
| Children
|--------------------------------------------------------------------------
|
--}}
@foreach($parent->children()->orderBy('created_at' , 'desc')->get() as $child)
	@include("manage.comments.show-one" , [
		'comment' => $child,
		'parent' => false ,
	]     )
@endforeach