@if($model->has('event'))
	<div class="panel panel-primary mv20">
		<div class="panel-heading">
			<i class="fa fa-calendar mh5"></i>
			{{ trans('posts.features.event') }}
		</div>
		
		<div class="panel-body bg-ultralight">

			{{--
			|--------------------------------------------------------------------------
			| Package and Availability
			|--------------------------------------------------------------------------
			|
			--}}


			<div class="row">
				<div class="col-md-6">

					{{-- Starts_at --}}
					@include("forms.datepicker" , [
						'in_form' => false,
						'name' => "starts_at",
						'top_label' => trans('validation.attributes.starts_at'),
						'class' => "ltr text-center",
						'value' => $model->starts_at,
						'label' => "",
					])

				</div>
				<div class="col-md-6">

					@include("forms.datepicker" , [
						'in_form' => false,
						'name' => "ends_at",
						'top_label' => trans('validation.attributes.ends_at'),
						'class' => "ltr text-center",
						'value' => $model->ends_at,
						'label' => "",
					])

				</div>
			</div>

		</div>
	</div>

@endif