
@include('manage.frame.use.tabs' , [
	'current' => $page[1][0] ,
	'tabs' => Manage::upstreamSettings() ,
])
