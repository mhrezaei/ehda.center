{{--
|--------------------------------------------------------------------------
| When 'domains' feature is set to be available in the posttype
|--------------------------------------------------------------------------
|
--}}

@if($model->has('domains'))
	{{ '' , $domains = user()->domainsQuery()->orderBy('title')->get() }}
	{{ '' , $show_combo = true }}

	{{--
	|--------------------------------------------------------------------------
	| When the user has no option, but to use his designated domain
	|--------------------------------------------------------------------------
	|
	--}}
	@if($domains->count() == 1 and !user()->is_not_a('manager'))
		@include("forms.hidden" , [
			'name' => "domains" ,
			'value' => $domains->first()->slug,
		]     )
		@include("forms.input-self" , [
			'name' => "" ,
			'top_label' => trans('validation.attributes.domain'),
			'value' => $domains->first()->title ,
			'extra' => "disabled" ,
		]     )
		{{ '' , $show_combo = false }}
	@endif

	{{--
	|--------------------------------------------------------------------------
	| When the user has a manager role and can set psots to 'global'
	|--------------------------------------------------------------------------
	|
	--}}
	@if(user()->is_a('manager'))
		{{ '' , $blank_value = 'global'}}
	@else
		{{ '' , $blank_value = 'NO' }}
	@endif


	{{--
	|--------------------------------------------------------------------------
	| The Combo Box
	|--------------------------------------------------------------------------
	|
	--}}
	@include("forms.select_self" , [
		'condition' => $show_combo,
		'top_label' => trans('validation.attributes.domain') ,
		'name' => "domains" ,
		'id' => "cmbDomain" ,
		'search' => true ,
		'value_field' => "slug" ,
		'blank_value' => $blank_value ,
		'blank_label' => trans('posts.form.global'),
		'on_change' => 'postDomainToggle()' ,
		'options' => $domains ,
		'value' => str_replace( '|' , null , str_replace('global' , null , $model->domains)) ,
	]     )



	{{--
	|--------------------------------------------------------------------------
	| Reflect to "global" checkbox
	|--------------------------------------------------------------------------
	|
	--}}
	@if(user()->is_a('manager'))

		@include("forms.check" , [
			'id' => "chkReflectInGlobal" ,
			'name' => "_reflect_in_global",
			'label' => trans("posts.form.reflect_in_global") ,
			'value' => str_contains($model->domains, 'global'),
		])

	@endif


@endif
{{-- Otherwise, a 'global' will be set in the controller's save() method --}}