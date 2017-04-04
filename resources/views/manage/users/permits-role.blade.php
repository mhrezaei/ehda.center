@if(user()->as($request_role->slug)->can("$module.*"))
	@include("forms.group-start" , [
		'label' => $label,
	])

	<div class="row w100 m5">
		@foreach($permissions as $permission)
			@if(user()->as($request_role->slug)->can("$module.$permission"))
				{{ '' , $input_name = "role~$module~$permission" }}

				<div class="col-md-3">
					<div class="checkbox">
						<label>
							<input type="hidden" name="{{$input_name}}" value="0">
							{!! Form::checkbox($input_name , '1' , $model->as($request_role->slug)->can("$module.$permission")? '1' : '0' , ['class' => '-permits']) !!}
							{{ trans('manage.permissions.'.$permission) }}
						</label>
					</div>
				</div>

			@endif
		@endforeach
	</div>

	@include("forms.group-end")
@endif