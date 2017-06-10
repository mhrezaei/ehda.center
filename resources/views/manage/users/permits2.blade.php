@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/permits'),
	'modal_title' => trans('people.commands.permit').' '.$model->full_name.' '.trans('people.form.as_a' , ['role_title' => $request_role->title ,]),
])
<div class='modal-body'>
	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
		['role_id' , $request_role->id],
	]])


	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	| Name and Status
	--}}
	@include("forms.textarea" , [
		'name' => "permissions",
		'id' => "txtPermissions" ,
		'class' => "ltr" ,
		'value' => $model->as($request_role)->getPermissions() ,
	]     )
	<button type="button" onclick="permitSpread()">UPDATE</button>

	@include("forms.select" , [
		'name' => "status" ,
		'id' => "cmbStatus-$request_role->id",
		'options' => $request_role->statusCombo() ,
		'value_field' => "0" ,
		'caption_field' => "1" ,
		'value' => $model->as($request_role->slug)->status() ,
		'condition' => $request_role->has_status_rules ,
	]     )


	{{--
	|--------------------------------------------------------------------------
	| People Management
	|--------------------------------------------------------------------------
	|
	--}}
	@if(isset($modules['users']))
		@include("forms.sep" , [
			'label' => trans("people.people_management").'...',
			'class' => "f16" ,
		]     )

		@foreach($roles as $role)
			@include("manage.users.permits2-module" , [
				'title' => $role->plural_title,
				'module' => "users-$role->slug" ,
				'permits' => $modules['users'] ,
			]     )
		@endforeach

		{{ '' , array_forget($modules , 'users') }}

	@endif

	{{--
	|--------------------------------------------------------------------------
	| Posts Management
	|--------------------------------------------------------------------------
	|
	--}}
	@if(isset($modules['posts']))
		@include("forms.sep" , [
			'label' => trans("manage.modules.posts"),
			'class' => "f16" ,
		])

		@foreach($posttypes as $posttype)
			@include("manage.users.permits2-module" , [
				'title' => $posttype->title,
				'module' => "posts-$posttype->slug" ,
				'permits' => $modules['posts'] ,
				'locales' => setting()->ask('site_locales')->gain() ,
			]     )
		@endforeach
	@endif

	{{ '' , array_forget($modules , 'posts') }}

	{{--
	|--------------------------------------------------------------------------
	| Other Modules
	|--------------------------------------------------------------------------
	|
	--}}
	@if(count($modules))
		@include("forms.sep" , [
			'label' => trans('manage.modules.other_modules'),
			'class' => "f16" ,
		])

		@foreach($modules as $module => $permits)
			@include("manage.users.permits2-module" , [
				'title' => trans("manage.modules.$module"),
				'module' => $module ,
				'permits' => $permits ,
			]     )
		@endforeach
	@endif

</div>
@include('templates.modal.end')


<script>
	function permitSpread() {
		var permission = $('#txtPermissions').val();

		$(".-permit").each(function () {
			var permit = $(this).attr('permit');
			if (permission.search(permit) >= 0) {
				$(this).children('.-permit-handle').addClass('fa-check-square-o').removeClass('fa-square-o');
				$(this).addClass('text-success').removeClass('text-gray');
			}
			else {
				$(this).children('.-permit-handle').removeClass('fa-check-square-o').addClass('fa-square-o');
				$(this).removeClass('text-success').addClass('text-gray');
			}
		})


	}
</script>