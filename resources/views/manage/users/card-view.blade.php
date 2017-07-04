@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/'),
	'modal_title' => trans('ehda.donation_card'),
])
<div class='modal-body profile {{ $model->withDisabled()->hasRole('card-holder')? '' : 'noDisplay' }}'>

	{{--
	|--------------------------------------------------------------------------
	| Header
	|--------------------------------------------------------------------------
	| Card Image and Name, card no, badges etc.
	--}}
	<div class="row">
		<div class="col-md-6">
				@include("manage.frame.widgets.grid-text" , [
					'text' => $model->full_name,
					'icon' => $model->gender_icon ,
					'size' => "30" ,
				]     )
				@include("manage.frame.widgets.grid-text" , [
					'text' => trans("ehda.donation_card").' '.trans("forms.general.no").' '.number_format($model->card_no),
					'size' => "20" ,
				]     )

				@include("manage.frame.widgets.grid-badge" , [
					'text' => trans("ehda.volunteer"),
					'icon' => "child" ,
					'color' => "info" ,
					'condition' => $model->is_admin() ,
				]     )
				@include("manage.frame.widgets.grid-badge" , [
					'text' => trans('people.newsletter_member'),
					'icon' => "envelope-o" ,
					'color' => "success" ,
					'condition' => $model->newsletter ,
				]     )
				@include("manage.frame.widgets.grid-badge" , [
					'text' => trans("forms.general.deleted"),
					'color' => "danger" ,
					'icon' => "times" ,
					'condition' => $model->as('card-holder')->disabled() ,
				]     )

			<div class="panel panel-violet m10" >
				<div class=" p20 panel-body bg-ultralight" style="min-height: 100px">
					<div class="f14 ltr {{$model->email? '' : 'noDisplay'}} mv10">
						<i class="fa fa-envelope-o mh10"></i>
						{{ $model->email }}
					</div>
					<div class="f14 ltr {{$model->mobile? '' : 'noDisplay'}}">
						<i class="fa fa-phone mh10"></i>
						{{ pd(formatPhone($model->mobile)) }}
					</div>
				</div>
			</div>

		</div>

		<div class="col-md-6">
			@if($model->as('card-holder')->enabled())
				<div>
					<a href="{{url("/card/show_card/full/$model->hash_id")}}" target="_blank">
						<img src="{{ $model->cards('single' , 'show') }}" style="width: 350px">
					</a>
				</div>
			@else
				<div>
					<div style="height: 200px ; width: 350px ; background-color: #d8d8d8 ; padding: 90px 180px ; color: #414141 ; border-radius: 5px ; margin-top: 20px" >X</div>
				</div>
			@endif
		</div>

	</div>

	<table>
		{{--
		|--------------------------------------------------------------------------
		| Personal Information
		|--------------------------------------------------------------------------
		|
		--}}
		<tr><td colspan="2"><hr></td></tr>

		<tr>
			<td class="head">
				{{ trans('validation.attributes.code_melli') }}
			</td>
			<td class="body">
				{{ pd($model->code_melli) }}
				@if($model->code_id)
					<span class="mh5 f10 text-gray">
						({{ trans("validation.attributes.code_id").': '.pd($model->code_id) }})
					</span>
				@endif
			</td>
		</tr>

		<tr>
			<td class="head">
				{{ trans('validation.attributes.birth_date') }}
			</td>
			<td class="body">
				{{ $model->birth_date_on_card }}
				<span>
					{{ $model->birth_city_name }}
				</span>
			</td>
		</tr>

		<tr>
			<td class="head">
				{{ trans('validation.attributes.education') }}
			</td>
			<td class="body">
				{{ trans("people.edu_level_full." . intval($model->edu_level))}}
				<span>
					{{ pd($model->edu_field) }}
				</span>
				<span>
					{{ $model->edu_city? $model->edu_city_name : '' }}
				</span>
			</td>
		</tr>

		<tr style="vertical-align: top">
			<td class="head">
				{{ trans('validation.attributes.home_city') }}
			</td>
			<td class="body">
				{{ $model->home_city_name }}
				{{ pd ($model->home_address) }}
				<div class="{{$model->home_postal_code? '' : 'noDisplay'}}">
					{{ trans('validation.attributes.postal_code') }}:&nbsp;
					{{ pd($model->home_postal_code) }}
				</div>
				<div class="{{$model->home_tel? '' : 'noDisplay'}}">
					{{ trans('validation.attributes.tel') }}:&nbsp;
					{{ pd($model->home_tel) }}
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

<div class='modal-footer rtl {{ $model->withDisabled()->hasRole('card-holder')? '' : 'noDisplay' }}'>
	@include("forms.button" , [
		'label' => trans("forms.button.card_print"),
		'shape' => "primary" ,
	]     )
	@include("forms.button" , [
		'condition' => $model->canEdit() ,
		'label' => trans("forms.button.edit"),
		'shape' => "warning" ,
	]     )
</div>


{{--
|--------------------------------------------------------------------------
| Error 410
|--------------------------------------------------------------------------
| When the person in question, doesn't have card-holder role, even in disabled mode.
--}}

<div class='modal-body profile {{ $model->withDisabled()->hasRole('card-holder')? 'noDisplay' : '' }}'>
	@include("errors.m410")
</div>
@include('templates.modal.end')