@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/permits'),
	'modal_title' => trans('people.commands.permit').' '.$model->full_name.' '.trans('people.form.as_a' , ['role_title' => $request_role->title ,]),
])
<div class='modal-body'>
	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
		['role_slug' , $request_role->slug],
	]])

	<div>

		{{--
		|--------------------------------------------------------------------------
		| Tab Bar
		|--------------------------------------------------------------------------
		|
		--}}
		<ul class="nav nav-tabs" role="tablist">
			@if(isset($modules['users']) )
				{{ '' , $module_users = $modules['users'] }}
				{{ '' , array_forget($modules , 'users') }}
				@if( in_array( $request_role->slug , model('role')::adminRoles()) and user()->as_any()->can("users"))
					<li role="presentation" class="active"><a href="#divPeoplePermits" aria-controls="divPeoplePermits"
															  role="tab"
															  data-toggle="tab">{{ trans("people.people_management") }}</a>
					</li>
				@endif
			@endif

			@if(isset($modules['posts']))
				{{ '' , $module_posts = $modules['posts'] }}
				{{ '' , array_forget($modules , 'posts') }}
				@if( in_array( $request_role->slug , model('role')::adminRoles()) and user()->as_any()->can("posts"))

					<li role="presentation"><a href="#divPostsPermits" aria-controls="divPostsPermits" role="tab"
										   data-toggle="tab">{{ trans("manage.modules.posts") }}</a></li>
				@endif
			@endif

			@if(count($modules))
				<li role="presentation"><a href="#divOtherPermits" aria-controls="divOtherPermits" role="tab"
										   data-toggle="tab">{{ trans('manage.modules.other_modules') }}</a></li>
			@endif
		</ul>

		{{--
		|--------------------------------------------------------------------------
		| Panels
		|--------------------------------------------------------------------------
		|
		--}}
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="divPeoplePermits">
				@foreach($roles as $role)
					@include("manage.users.permits2-module" , [
						'title' => $role->plural_title,
						'module' => "users-$role->slug" ,
						'permits' => $module_users ,
					]     )
				@endforeach
				@include("manage.users.permits2-module" , [
					'title' => trans("people.commands.all_users"),
					'module' => "users-all" ,
					'permits' => $module_users ,
				]     )
			</div>

			<div role="tabpanel" class="tab-pane" id="divPostsPermits">
				@foreach($posttypes as $posttype)
					@include("manage.users.permits2-module" , [
						'title' => $posttype->title,
						'module' => "posts-$posttype->slug" ,
						'permits' => $module_posts ,
						'locales' => setting()->ask('site_locales')->gain() ,
					]     )
				@endforeach
			</div>

			<div role="tabpanel" class="tab-pane" id="divOtherPermits">
				@foreach($modules as $module => $permits)
					@include("manage.users.permits2-module" , [
						'title' => trans("manage.modules.$module"),
						'module' => $module ,
						'permits' => $permits ,
					]     )
				@endforeach
			</div>
		</div>

	</div>


	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	| Name and Status
	--}}
	@include("forms.textarea" , [
		'name' => "permissions",
		'id' => "txtPermissions" ,
		'class' => "ltr noDisplay" ,
		'value' => $model->as($request_role)->getPermissions() ,
		'in_form' => false ,
	]     )

	{{--@include("forms.select" , [--}}
		{{--'name' => "status" ,--}}
		{{--'id' => "cmbStatus-$request_role->id",--}}
		{{--'options' => $request_role->statusCombo() ,--}}
		{{--'value_field' => "0" ,--}}
		{{--'caption_field' => "1" ,--}}
		{{--'value' => $model->as($request_role->slug)->status() ,--}}
		{{--'condition' => $request_role->has_status_rules ,--}}
	{{--]     )--}}

	{{--
	|--------------------------------------------------------------------------
	| Save Button
	|--------------------------------------------------------------------------
	|
	--}}
	@include("forms.sep")
		@include('forms.button' , [
			'label' => trans('forms.button.save'),
			'shape' => 'primary',
			'type' => 'submit' ,
		])
		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => '$(".modal").modal("hide")',
		])

	<div class="m5"></div>
	@include('forms.feed')


</div>
@include('templates.modal.end')
<script>permitSpread()</script>