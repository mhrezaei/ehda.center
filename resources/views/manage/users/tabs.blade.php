@include('manage.frame.use.tabs' , [
	'current' =>  $page[1][0] ,
	'tabs' => [
		["actives" , trans('people.criteria.actives')],
		['banned' , trans('people.criteria.banned') ],
		['search' , trans('forms.button.search')],
	] ,
])