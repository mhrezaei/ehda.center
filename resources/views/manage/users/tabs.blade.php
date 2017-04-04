@include('manage.frame.use.tabs' , [
	'current' =>  $page[1][0] ,
	'tabs' => [
		["actives" , trans('people.criteria.actives')],
		['banned' , trans('people.criteria.banned') , '0' , ($request_role != 'all' and user()->as($request_role)->can('banned')) ],
		['bin' , trans('manage.tabs.bin') , '0' , ($request_role == 'all') ],
		['search' , trans('forms.button.search')],
	] ,
])