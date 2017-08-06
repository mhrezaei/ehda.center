@include('templates.modal.start' , [
//	'form_url' => url('manage/cards/save/send-to-printings'),
	'modal_title' => trans('ehda.volunteers.single'),
])
<div class='modal-body profile {{ $model->withDisabled()->is_an('admin')? '' : 'noDisplay' }}'>

	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	| Card Image and Name, card no, badges etc.
	--}}
	<div class="row w90">
		<div class="col-md-6">
				@include("manage.frame.widgets.grid-text" , [
					'text' => $model->full_name,
					'icon' => $model->gender_icon ,
					'size' => "30" ,
				]     )


				@if($model->is_a('card-holder'))
					@include("manage.frame.widgets.grid-badge" , [
						'text' => trans("ehda.donation_card") ,
						'color' => "success" ,
						'link' => user()->as('admin')->can('users-card-holder.view')? "modal:manage/cards/view/-hash_id-" : '',
						'size' => "9" ,
						'icon' => "credit-card" ,
					]     )
				@else
					@include("manage.frame.widgets.grid-badge" , [
						'text' => trans("ehda.without_donation_card") ,
						'color' => "danger" ,
						'icon' => "credit-card" ,
						'size' => "9" ,
					]     )
				@endif

		</div>

		<div class="col-md-6 ltr" style="direction: ltr">

			<div class="f14 ltr {{$model->email? '' : 'noDisplay'}} mv10">
				<i class="fa fa-envelope-o mh10"></i>
				{{ $model->email }}
			</div>
			<div class="f14 ltr {{$model->mobile? '' : 'noDisplay'}}">
				<i class="fa fa-phone mh10"></i>
				{{ ed(formatPhone($model->mobile)) }}
			</div>

 		</div>

	</div>

	<table >
		{{--
		|--------------------------------------------------------------------------
		| Personal Information
		|--------------------------------------------------------------------------
		|
		--}}
		@include("manage.users.card-view-personal")

		{{--
		|--------------------------------------------------------------------------
		| Activity
		|--------------------------------------------------------------------------
		|
		--}}
		<tr><td colspan="2"><hr></td></tr>

		<tr>
			<td class="head" valign="top">
				{{ trans('validation.attributes.activities') }}
			</td>

			<td class="body">

				<div class="row">


					<div class="col-md-6">
						@include("manage.frame.widgets.grid-text" , [
							'fake' => $count = $model->posts()->count() ,
							'fake2' => !$count? $count = trans('forms.general.without') : null ,
							'text' => pd($count) . ' ' . trans("people.form.cooperation_in_posts") ,
							'size' => "11" ,
							'icon' => "clipboard" ,
						]     )

						@include("manage.frame.widgets.grid-text" , [
							'fake' => $count = $model->cardRegisters()->count() ,
							'fake2' => !$count? $count = trans('forms.general.without') : null ,
							'text' => pd($count) . ' ' . trans("people.form.cooperation_in_card_registers") ,
							'size' => "11" ,
							'icon' => "credit-card" ,
						]     )
						@include("manage.frame.widgets.grid-text" , [
							'fake' => $count = $model->cardPrintings()->count() ,
							'fake2' => !$count? $count = trans('forms.general.without') : null ,
							'text' => pd($count) . ' ' . trans("people.form.cooperation_in_card_printings") ,
							'size' => "11" ,
							'icon' => "print" ,
						]     )

					</div>


				{{-- Site Activities -------------------------------}}
					<div class="col-md-6">
						
						{{-- Selected Activities -------------------------------}}
						@foreach($model->activity_captions_array as $item)
							<div class="mv5">
								<i class="fa fa-check mh5"></i>
								{{ $item }}
							</div>
						@endforeach

						{{-- Alloc_time -------------------------------}}
						@if($model->alloc_time)
							<div class="mv5">
								<i class="fa fa-clock-o mh5"></i>
								({{pd($model->alloc_time)}})
							</div>
						@endif
					</div>
				</div>
			</td>

		</tr>


		{{--
		|--------------------------------------------------------------------------
		| Roles
		|--------------------------------------------------------------------------
		|
		--}}
		<tr><td colspan="2"><hr></td></tr>

		<tr>
			<td class="head">
				{{ trans("people.user_role") }}
			</td>

			<td>
				<div class="mh20">
					@include("manage.users.volunteer-view-roles")
				</div>
			</td>
		</tr>







		{{--
		|--------------------------------------------------------------------------
		| Timestamps
		|--------------------------------------------------------------------------
		|
		--}}
		<tr><td colspan="2"><hr></td></tr>

		<tr>
			<td class="head">
				{{ trans('people.membership') }}
			</td>
			<td class="body">
				{{ pd(echoDate($model->card_registered_at)) }}
				@if($model->created_by)
					<span>
						{{ trans('forms.general.by'). ' ' . $model->creator->full_name }}
					</span>
				@endif
			</td>
		</tr>

		<tr>
			<td class="head">
				{{ trans('forms.general.updated_at') }}
			</td>
			<td class="body">
				{{ pd(echoDate($model->updated_at)) }}
				@if($model->updated_by)
					<span>
						{{ trans('forms.general.by').' '.$model->updater->full_name }}
					</span>
				@endif
			</td>
		</tr>

		@if($model->as('card-holder')->disabled())
			<tr class="text-danger">
				<td class="head">
					{{ trans('forms.general.deleted_at') }}
				</td>
				<td class="body">
					{{ pd(echoDate($model->as('card-holder')->pivot('deleted_at'))) }}
				</td>

			</tr>
		@endif

	</table>

</div>


{{--
|--------------------------------------------------------------------------
| Buttons
|--------------------------------------------------------------------------
|
--}}

<div class='modal-footer {{ $model->withDisabled()->hasRole('card-holder')? '' : 'noDisplay' }}'>
	@if($model->canEdit())
		<a class="btn btn-default btn-lg w35" href="{{ url("manage/volunteers/edit/$model->hash_id") }}">
			{{ trans("forms.button.edit") }}
		</a>
	@endif

	{{--@include("forms.button" , [--}}
		{{--'label' => trans("forms.button.card_print"),--}}
		{{--'shape' => "default" ,--}}
		{{--'class' => "btn-lg w30" ,--}}
		{{--'link' => "$('#divSendToPanel').slideToggle('fast')" ,--}}
	{{--]     )--}}

	@include("manage.printings.send-to-panel" , [
		'user_id' => $model->id ,
	]     )

</div>



{{--
|--------------------------------------------------------------------------
| Error 410
|--------------------------------------------------------------------------
| When the person in question, doesn't have card-holder role, even in disabled mode.
--}}

<div class='modal-body profile {{ $model->withDisabled()->is_an('admin')? 'noDisplay' : '' }}'>
	@include("errors.m410")
</div>
@include('templates.modal.end')