@include('manage.frame.use.tabs' , [
	'current' => $page[1][0] ,
	'tabs' => [
		['password' , trans('people.commands.change_password') ],
		['profile' , trans('people.commands.profile') ],
		['card' , trans('ehda.donation_card') ],
		['delete' , trans('people.commands.delete_account') ],
	] ,
])
