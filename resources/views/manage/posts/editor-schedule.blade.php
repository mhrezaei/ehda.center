@if($model->has('schedule'))
	<div id="divSchedule" class="panel panel-default text-center {{ $model->isScheduled()? '' : 'noDisplay' }}">
		<div class="panel-heading text-right">{{ trans('posts.form.adjust_publish_time') }}</div>
			<div class="panel-body m10">

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
				])


				{{--
				|--------------------------------------------------------------------------
				| Time Input
				|--------------------------------------------------------------------------
				|
				--}}
				<div class="text-center m10">
					@include('forms.select_self' , [
						'id' => "cmbPublishDate" ,
						'name' => 'publish_time' ,
						'value' => $model->published_at? jdate($model->published_at)->format('h:i') : "08:00",
//						'value' => $model->published_at? 'custom' : 'auto' ,
						'options' => clockArray() ,
						'search' => 1,
					])
				</div>

				{{--
				|--------------------------------------------------------------------------
				| Toggle Botton
				|--------------------------------------------------------------------------
				|
				--}}
				<a href="#" onclick="postToggleSchedule('hide')" class="btn btn-link btn-xs">{{ trans('posts.form.discard_schedule') }}</a>

			</div>
		</div>

	</div>
@endif