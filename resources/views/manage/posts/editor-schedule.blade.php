@if($model->has('schedule'))
	<div id="divSchedule" class="panel panel-default text-center {{ $model->isScheduled()? '' : 'noDisplay' }}">
		<div class="panel-heading text-right">
			<span class="pull-left">
				<label class="fa fa-times text-gray clickable" onclick="postToggleSchedule('hide')"></label>
			</span>
			{{ trans('posts.form.adjust_publish_time') }}
		</div>
		<div class="panel-body">

			{{--
			|--------------------------------------------------------------------------
			| Date Input
			|--------------------------------------------------------------------------
			|
			--}}
			@include('forms.datepicker' , [
				'name' => 'publish_date' ,
				'id' => 'txtPublishDate' ,
				'value' => $model->published_at ,
				'in_form' => 0 ,
				'placeholder' => trans('validation.attributes.published_at'),
				'class' => "text-center",
			])


			{{--
			|--------------------------------------------------------------------------
			| Time Input
			|--------------------------------------------------------------------------
			|
			--}}
			<div class="text-center m10">
				<div class="input-group input-group-sm">
					<input id="txtScheduleM" name="publish_minute" value="{{ $model->isScheduled()? jdate($model->published_at)->format('i') : ''}}" type="text" class="form-control ltr text-center" onblur=""  placeholder="50" min="0" max="59">
					<span class="input-group-addon">:</span>
					<input id="txtScheduleH" name="publish_hour" value="{{ $model->isScheduled()? jdate($model->published_at)->format('H') : ''}}" type="text" class="form-control ltr text-center" onblur="$('#txtScheduleM').focus()" placeholder="13" min="0" max="23" >
				</div>

			</div>

		</div>

	</div>
@endif