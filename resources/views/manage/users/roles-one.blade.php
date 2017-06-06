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
				'fake' => $role->has_modules? $status = " (".$role->statusRule( $model->as($role->slug)->status() , true ).") " : $status = "" ,
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
	<div class="col-md-6">
		@include("forms.select_self" , [
			'name' => "status" ,
			'id' => "cmbStatus-$role->slug",
			'options' => $role->statusCombo() ,
			'value_field' => "0" ,
			'caption_field' => "1" ,
			'value' => "" ,
		]     )
	</div>


	{{--
	|--------------------------------------------------------------------------
	| Save Button
	|--------------------------------------------------------------------------
	|
	--}}
	<div class="col-md-3">
		<button type="button" class="btn btn-primary">
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
			'class' => "btn btn-default" ,
			'icon' => "undo" ,
		]     )
	</div>
</div> 