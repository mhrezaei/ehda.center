<div class="refresh">{{ url("manage/users/act/$model->id/refreshRoleRow/$role->id") }}</div>

<div class="row pv5 -summery">

	{{--
	|--------------------------------------------------------------------------
	| Role Summery
	|--------------------------------------------------------------------------
	| Check if the role is attached, and if so, is it active or not.
	| Also writes the currenct status if defined. 
	--}}

	<div class="col-md-9">
		@if($model->withDisabled()->is_not_a($role->slug))
			@include("manage.frame.widgets.grid-text" , [
				'color' => "gray",
				'icon' => "times" ,
				'text' => '' ,
			]     )
		@elseif($model->as($role->slug)->enabled())
			@include("manage.frame.widgets.grid-text" , [
				'color' => "success",
				'icon' => "check" ,
				'fake' => $role->has_status_rules? $status = " (".$role->statusRule( $model->as($role->slug)->status()  , true).") " : $status = "" ,
				'text' => trans('people.form.now_active').$status ,
			]     )
		@else
			@include("manage.frame.widgets.grid-text" , [
				'color' => "danger",
				'icon' => "ban",
				'text' => trans('people.form.now_blocked') ,
			]     )
		@endif
	</div>

	{{--
	|--------------------------------------------------------------------------
	| Change Button
	|--------------------------------------------------------------------------
	| 
	--}}
	<div class="col-md-3">
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans('forms.button.edit'),
			'link' => "$('#divRole-$role->id .-summery , #divRole-$role->id .-edit').slideToggle('fast')" ,
			'class' => "btn btn-default btn-xs" ,
			'icon' => "pencil" ,
		]     )

	</div>

</div>


<div class="row -edit noDisplay">
	{{--
	|--------------------------------------------------------------------------
	| Combo
	|--------------------------------------------------------------------------
	|
	--}}
	<?php
	if($model->withDisabled()->is_not_a($role->slug)) {
		$current_status         = '';
		$include_delete_options = false;
	}
	elseif($model->as($role->slug)->disabled()) {
		$current_status         = 'ban';
		$include_delete_options = true;
	}
	else {
		$current_status         = $model->as($role->slug)->status();
		$include_delete_options = true;
	}

	?>
	<div class="col-md-6">
		@include("forms.select_self" , [
			'name' => "status" ,
			'id' => "cmbStatus-$role->id",
			'options' => $role->statusCombo($include_delete_options) ,
			'value_field' => "0" ,
			'caption_field' => "1" ,
			'value' => $current_status ,
			'blank_value' => $include_delete_options? 'NO' : '' ,
			'on_change' => "roleAttachmentEffect( '$role->id')" ,
			'initially_run_onchange' => false,
		]     )
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Save Button
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-3">
		<button id="btnRoleSave-{{$role->id}}" type="button" class="btn noDisplay" onclick="roleAttachmentSave('{{$model->id}}' , '{{$role->id}}' , '{{$role->slug}}')">
			{{ trans('forms.button.save') }}
		</button>
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Cancel Button
	|--------------------------------------------------------------------------
	| 
	--}}
	<div class="col-md-3">

		@include("manage.frame.widgets.grid-text" , [
			'text' => trans('forms.button.cancel'),
			'link' => "$('#divRole-$role->id .-summery , #divRole-$role->id .-edit').slideToggle('fast')" ,
			'class' => "btn btn-default btn-xs" ,
			'icon' => "undo" ,
		]     )
	</div>
</div>

<script>
	function roleAttachmentEffect(role_id) {
		var new_status = $("#cmbStatus-" + role_id).val();
		var $button = $("#btnRoleSave-" + role_id);
		$button.removeClass('btn-warning btn-primary btn-danger');

		switch (new_status) {
			case 'ban' :
				$button.addClass('btn-warning');
				break;
			case 'detach' :
				$button.addClass('btn-danger');
				break;
			default :
				$button.addClass('btn-primary');
				break;
		}
		$button.fadeIn('fast');
	}

	function roleAttachmentSave(user_id , role_id , role_slug) {
	    var new_status = $("#cmbStatus-" + role_id).val();
	    var $button = $("#btnRoleSave-" + role_id);

	    $.ajax({
		    url:url('manage/users/save/role/'+user_id+'/'+role_slug+'/'+new_status) ,
		    dataType: "json",
		    cache: false
	    })
		    .done(function(result) {
				divReload("divRole-"+role_id);
				rowUpdate('tblUsers' , user_id);
		    });

    }
</script>