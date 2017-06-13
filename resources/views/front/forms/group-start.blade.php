@if(!isset($condition) or $condition)

	<div class="form-group">
		<label class="col-sm-2 control-label {{$label_class or ''}}">
			{{$label or ' '}}
			@if(isset($required) and $required)
				<span class="fa fa-star required-sign " title="{{trans('forms.logic.required')}}"></span>
			@endif
		</label>

		<div class="col-sm-10 {{$div_class or ''}}">

			@if(0)
		</div>
	</div>
	@endif
@endif