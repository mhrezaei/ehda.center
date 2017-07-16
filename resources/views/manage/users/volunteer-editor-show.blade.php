{{--
|--------------------------------------------------------------------------
| Name and Current Roles
|--------------------------------------------------------------------------
|
--}}


<div class="row w90">
	<div class="col-md-5 text-center">
		<h2>
			{{ $model->full_name }}
		</h2>

		<a href="{{v0()}}" class="btn btn-default btn-lg mh10 w80" onclick="$('#divNewRole').slideDown('fast')">{{ trans("ehda.volunteers.add_role") }}</a>
		@if($model->canEdit())
			<a href="{{v0()}}" class="btn btn-default btn-lg mh10 mv5 w80">{{ trans("forms.button.edit_info") }}</a>
		@endif

	</div>


	<div class="col-md-7 pv30">

		@foreach($model->withDisabled()->rolesQuery() as $role)
			<div class="mv10">
				{{ '' , $role_model = model('role' , $role['id'])}}
				{{ '' , $role['pivot']['deleted_at']? $status = 'banned' : $status = $role_model->statusRule( $role['pivot']['status'] )}}

				@if($status == 'active')
					{{ '' , $color = 'success' }}
				@elseif($status=='banned')
					{{ '' , $color = 'danger' }}
				@else
					{{ '' , $color = 'darkgray' }}
				@endif

					@include("manage.frame.widgets.grid-text" , [
						'text' => $role['title'] . ': ' . trans("people.criteria.$status"),
						'size' => "16" ,
						'icon' => trans("people.criteria_icon.$status") ,
						'color' =>  $color,
						'condition' => $role_model->slug != 'card-holder' ,
						'link' => $model->as($role_model->slug)->canEdit()? "modal:manage/users/act/-id-/user-status/$role_model->slug" : '' ,
					]     )
			</div>

		@endforeach

	</div>
</div>









{{--
|--------------------------------------------------------------------------
| New Role Panel
|--------------------------------------------------------------------------
|
--}}
{{ '' , $array = user()->userRolesArray('create' , array_add( $model->withDisabled()->rolesArray()  , 'n' , 'card-holder') )}}
{{ '' , $combo = model('role')::whereIn('slug' , $array)->orderBy('title')->get() }}

<div id="divNewRole" class="noDisplay panel panel-primary w80">

	<div class="panel-heading text-bold">
		{{ trans("ehda.volunteers.add_role") }}
		<a class="fa fa-times pull-left text-white clickable" onclick="$('#divNewRole').slideUp('fast')"></a>
	</div>

	<div class="panel-footer">
		
		
		@include("forms.opener" , [
			'url' => url('manage/volunteers/save/new-role'),
			'class' => "js" ,
		]     )

		@include('forms.hiddens' , ['fields' => [
			['id' , isset($model)? $model->id : '0'],
		]])

		@include("forms.select" , [
			'name' => "role_slug",
			'label' => trans("people.user_role") ,
			'blank_value' => "" ,
			'value' => $option ,
			'options' => $combo ,
			'search' => true ,
			'value_field' => "slug" ,
		]     )

		@include("forms.select" , [
			'name' => "status" ,
			'value' => 8 ,
			'options' => $role_model->statusCombo() ,
			'caption_field' => "1" ,
			'value_field' => "0" ,
		]     )


		@include('forms.group-start')

		@include('forms.button' , [
			'label' => trans('forms.button.save'),
			'shape' => 'primary',
			'type' => 'submit' ,
		])
		@include('forms.button' , [
			'label' => trans('forms.button.cancel'),
			'shape' => 'link',
			'link' => "$('#divNewRole').slideUp('fast')",
		])

		@include('forms.group-end')

		@include('forms.feed')




		@include("forms.closer")
		
		
	</div>

</div>