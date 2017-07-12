{{ '' , $actions = [] }}
{{ '' , $allowed_rules = user()->userRolesArray() }}

{{ '' , $actions[] = [
		$request_role == 'admin' ? 'chevron-left' : 'angle-left' ,
		$request_role == 'admin' ? "--[ " . trans("people.criteria.all") . " ]--" : trans("people.criteria.all"),
		url("manage/volunteers/browse/all"),
		true ,
] }}

@foreach(model('domain')::orderBy('title')->get() as $domain)

	@if($request_role == "volunteer-$domain->slug")
		{{ '' , $actions[] = [
			'caret-left'  ,
			"--[ $domain->title ]--" ,
			url("manage/volunteers/browse/$domain->slug/8"),
			in_array('volunteer-'.$domain->slug , $allowed_rules),
		] }}
	@else
		{{ '' , $actions[] = [
			'angle-left' ,
			$domain->title,
			url("manage/volunteers/browse/$domain->slug/8"),
			in_array('volunteer-'.$domain->slug , $allowed_rules),
		] }}
	@endif

@endforeach
@include('manage.frame.widgets.grid-action' , [
	'id' => '0',
	'button_size' => 'sm' ,
	'button_class' => 'default' ,
	'button_label' => trans("people.commands.according_to_domain"),
	'actions' => $actions ,
])
