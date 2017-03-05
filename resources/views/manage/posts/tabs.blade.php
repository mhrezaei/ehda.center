@include('manage.frame.use.tabs' , [
//	'refresh_url' => str_replace('?page=1',null, str_replace('posts/' , 'posts/tab_update/' , $models->url(1)   ))  ,
	'current' =>  $page[1][0] ,
	'tabs' => [
		["published" , trans('posts.criteria.published')],
		["scheduled" , trans('posts.criteria.scheduled')],
		["pending" , trans('posts.criteria.pending')],
		["my_posts" , trans('posts.criteria.my_posts')],
		["my_drafts" , trans('posts.criteria.my_drafts')],
		["bin" , trans('manage.tabs.bin') ],// , $db->counterC('bin')],
		["search" , trans('forms.button.search')],
	] ,
])
