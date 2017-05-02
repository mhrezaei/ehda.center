@include('manage.frame.use.tabs' , [
//	'refresh_url' => str_replace('?page=1',null, str_replace('posts/' , 'posts/tab_update/' , $models->url(1)   ))  ,
	'current' =>  $page[1][0]."/$switch" ,
	'tabs' => [
		["all/$switch" , trans('posts.criteria.all')],
		["pending/$switch" , trans('posts.criteria.pending')],
		["approved/$switch" , trans('posts.criteria.approved')],
		["private/$switch" , trans('posts.criteria.private')],
		["bin/$switch" , trans('manage.tabs.bin') ],// , $db->counterC('bin')],
//		["$locale/search" , trans('forms.button.search')],
	] ,
])