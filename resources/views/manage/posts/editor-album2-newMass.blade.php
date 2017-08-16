{{--
|--------------------------------------------------------------------------
| This is called when a new file is inserted and will be appended at the end of previous inserted files.
|--------------------------------------------------------------------------
| $option is passed via the automatic ManageControllerTrait method, and contains the hashid of newly inserted file.
--}}

@if(isset($option))
	{{ '' , $items =  array_filter(explode('-' , $option))}}
	{{ '' , $last_key = isset($items[0]) ? intval($items[0]) : 0}}

	@foreach($items as $key => $item)
		@if($key > 0 )
			@include('manage.posts.editor-album2-one' , [
				'key' => $last_key + $key ,
				'src' => $item ,
				'label' => null ,
				'link' => null ,
			])
			<script>postFileCounterUpdate('+')</script>
		@endif
	@endforeach

@endif


