@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/permits'),
	'modal_title' => trans('people.commands.permit'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
		['role_id' , $request_role->id],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.name_first'),
		'value' => $model->full_name ,
		'extra' => 'disabled' ,
	])

	@include("forms.input" , [
		'name' => "",
		'value' => $request_role->title ,
		'label' => trans('people.user_role'),
		'disabled' => true,
 	])

	{{--
	|--------------------------------------------------------------------------
	| Superadmin
	|--------------------------------------------------------------------------
	|
	--}}


	@include("forms.select" , [
		'condition' => ($request_role->slug=='admin' and user()->isSuper()),
		'name' => "level",
		'label' => trans('people.admins.admin_type'),
		'value' => $model->isSuper()? 'super' : 'ordinary',
		'options' => [
			['ordinary' , trans('people.admins.ordinary_admin')] ,
			['super' , trans('people.admins.super_admin')],
		],
		'value_field' => "0",
		'caption_field' => "1",
		'hint' => trans('people.admins.superAdmin_hint'),
	])

	{{--
	|--------------------------------------------------------------------------
	| Users
	|--------------------------------------------------------------------------
	|
	--}}
	@if($permissions = array_pull($modules , 'users') and $roles->count()>0)
		@include("forms.sep" , [
			'label' => trans("manage.modules.users"),
		])

		@foreach($roles as $role)
			@include("manage.users.permits-role" , [
				'module' => "users-$role->slug",
				'label' => $role->plural_title,
			])
		@endforeach
	@endif

	{{--
	|--------------------------------------------------------------------------
	| Posts
	|--------------------------------------------------------------------------
	|
	--}}
	@if($permissions = array_pull($modules , 'posts'))
		@include("forms.sep" , [
			'label' => trans("manage.modules.posts"),
		])

		@foreach($posttypes as $posttype)
			@include("manage.users.permits-role" , [
				'module' => "posts-$posttype->slug",
				'label' => $posttype->title,
			])
		@endforeach
	@endif

	{{--
	|--------------------------------------------------------------------------
	| Role Browser
	|--------------------------------------------------------------------------
	| 'users' and 'posts' will be processed based on the available roles and posttypes
	--}}
	@include("forms.sep" , [
		'label' => trans('manage.modules.other_modules'),
		'condition' => count($modules),
	])
	@foreach($modules as $module => $permissions)
		@if(!in_array($module , ['posts' , 'users']))
			@include("manage.users.permits-role" , [
				'module' => $module,
				'label' => trans("manage.modules.$module"),
			])
		@endif
	@endforeach


	{{--
	|--------------------------------------------------------------------------
	| Buttons
	|--------------------------------------------------------------------------
	|
	--}}
	@include("forms.sep")
	@include('forms.group-start')

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

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')