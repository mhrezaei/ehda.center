@include('manage.frame.use.tabs' , [
//	'refresh_url' => str_replace('?page=1',null, str_replace('posts/' , 'posts/tab_update/' , $models->url(1)   ))  ,
	'current' =>  $page[1][0] ,
	'tabs' => [
		["all" , trans('posts.criteria.all')],
		["pending" , trans('posts.criteria.pending')],
		["approved" , trans('posts.criteria.approved')],
		["private" , trans('posts.criteria.private')],
		["bin" , trans('manage.tabs.bin') ],// , $db->counterC('bin')],
//		["$locale/search" , trans('forms.button.search')],
	] ,
])
