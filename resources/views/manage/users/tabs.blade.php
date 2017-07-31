@include('manage.frame.use.tabs' , [
	'current' =>  $page[1][0] ,
	'tabs' => $switches['browse_tabs'] == 'auto' ? $role->browseTabs() : $switches['browse_tabs']  ,
])