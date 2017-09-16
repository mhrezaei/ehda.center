@include('manage.frame.use.tabs' , [
//	'refresh_url' => str_replace('?page=1',null, str_replace('posts/' , 'posts/tab_update/' , $models->url(1)   ))  ,
	'current' =>  $page[1][0]."/$switch" ,
	'tabs' => [
		["all/$switch" , trans('posts.criteria.all')],
		["succeeded/$switch" , trans('forms.status_text.succeeded')],
		["on_hold/$switch" , trans('forms.status_text.on_hold')],
		["canceled/$switch" , trans('forms.status_text.canceled')],
	] ,
])