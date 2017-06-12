@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/role'),
	'modal_title' => trans('people.user_role').' '.$model->full_name,
])
<div class='modal-body'>
	@include('forms.hiddens' , ['fields' => [
		['id' , isset($model)? $model->id : '0'],
	]])

	@foreach($model->rolesTable() as $role)
		@include("forms.group-start" , [
			'label' => trans("people.form.as_a" , [	"role_title" => $role->title ,]),
		])
			<div id="divRole-{{$role->id}}">
				@include("manage.users.roles-one")
			</div>
		@include('forms.group-end')
	@endforeach

	@include('forms.feed')

</div>
@include('templates.modal.end')