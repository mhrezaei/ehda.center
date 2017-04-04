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
			<div class="row" data-content="{{ '' , $status = $model->as($role->slug)->status }}">
				<div class="col-md-6 mv5">
					@include("manage.frame.widgets.grid-badge" , [
						'color' => trans("forms.status_color.$status"),
						'text' => trans("people.form.now_$status"),
						'icon' => trans("forms.status_icon.$status"),
					])
{{--					<span class="text-{{ trans("forms.status_color.$status") }}">{{ trans("people.form.now_$status") }}</span>--}}
				</div>
				<div class="col-md-6">
					
					@include("forms.button" , [
						'condition' => $status=='without',
						'shape' => "primary",
						'type' => "submit",
						'value' => "attach-$role->id",
						'label' => trans("people.form.attach_this_role"),
						'size' => "12",
					])
					
					@include("forms.button" , [
						'condition' => $status=='active',
						'shape' => "warning",
						'type' => "submit",
						'value' => "block-$role->id",
						'label' => trans('people.commands.block'),
					])

					@include("forms.button" , [
						'condition' => $status=='blocked',
						'shape' => "success",
						'type' => "submit",
						'value' => "unblock-$role->id",
						'label' => trans('people.commands.unblock'),
					])

					@include("forms.button" , [
						'condition' => $status!='without',
						'shape' => "danger",
						'type' => "submit",
						'value' => "detach-$role->id",
						'label' => trans('people.form.detach_this_role'),
					])
					
					
				</div>
			</div>

		@include("forms.group-end")
	@endforeach

	@include('forms.feed')

</div>
@include('templates.modal.end')