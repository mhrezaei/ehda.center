
@include('manage.frame.use.tabs' , [
	'current' => $page[1][0] ,
	'tabs' => [
		['downstream' , trans('settings.downstream')],
		['posttypes' , trans('settings.posttypes')],
		['roles' , trans('settings.roles')],
		['packages' , trans('settings.packages')],
		['states' , trans('settings.states')],
	] ,
])
