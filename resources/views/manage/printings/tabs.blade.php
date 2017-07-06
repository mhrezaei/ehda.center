@include('manage.frame.use.tabs' , [
	'current' => "$request_tab/$event_id/$user_id/$volunteer_id" ,
	'fake' => !isset($volunteer_id)? $volunteer_id = 0 : 1,
	'fake2' => $switches = [
		'event_id' => $event_id,
		'user_id' => $user_id,
		'volunteer_id' => $volunteer_id,
	],
	'tabs' => [
		["pending/$event_id/$user_id/$volunteer_id" , trans('ehda.printings.pending') , null /*, $db::counter($switches , 'pending' , 'info')*/  ],
		["under_direct_printing/$event_id/$user_id/$volunteer_id" , trans('ehda.printings.under_direct_printing') , null   ],
		["under_excel_printing/$event_id/$user_id/$volunteer_id" , trans('ehda.printings.under_excel_printing') , null  ],
	] ,
])
