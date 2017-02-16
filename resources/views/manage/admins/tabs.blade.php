@include('manage.frame.use.tabs' , [
	'refresh_url' => "manage/admins/update/tab",
	'current' =>  $page[1][0] ,
	'tabs' => [
		['browse/actives' , trans('manage.tabs.actives') , $db->counterC('actives')],
		['browse/bin' , trans('manage.tabs.bin') , $db->counterC('bin')],
		['search' , trans('forms.button.search')],
	] ,
])
