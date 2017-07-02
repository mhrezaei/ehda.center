@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/'),
	'modal_title' => trans('ehda.donation_card'),
])
<div class='modal-body profile'>

	{{--
	|--------------------------------------------------------------------------
	| Card Image
	|--------------------------------------------------------------------------
	|
	--}}
	@if($model->as('card-holder')->enabled())
		<div style="position:absolute;top: 10px;left: 10px">
			<a href="{{url("/card/show_card/full/$model->hash_id")}}" target="_blank">
				<img src="{{url("/card/show_card/mini/$model->hash_id")}}" style="height: 450px">
			</a>
			{{--<div class="text-center" style="position: relative ; top: -80px;">--}}
				{{--@if(Auth::user()->can($model->isActiveVolunteer()? 'volunteers.edit' : 'cards.edit'))--}}
					{{--<a href="{{ url("manage/cards/$model->id/edit") }}" class="btn btn-lg btn-primary">{{ trans('people.cards.manage.edit') }}</a>--}}
				{{--@endif--}}
			{{--</div>--}}
		</div>
	@else
		<div style="position:absolute;top: 10px;left: 10px">
			<div style="height: 410px ; width: 330px ; background-color: #d8d8d8 ; padding: 220px 180px ; color: #414141 ; border-radius: 5px">X</div>
		</div>
	@endif



	{{--
	|--------------------------------------------------------------------------
	| Name, Badges and Card No
	|--------------------------------------------------------------------------
	|
	--}}

	<h2 class="mv20">
		<i class="fa fa-{{$model->gender_icon}}"></i>
		{{ $model->full_name }}
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
	</h2>

	<h3>
		@include("manage.frame.widgets.grid-text" , [
			'text' => trans("ehda.donation_card").' '.trans("forms.general.no").' '.$model->card_no,
			'size' => "20" ,
		]     )
	</h3>

	<div class="panel panel-violet mh10" style="width: 30%">
		<div class=" p5 panel-body bg-ultralight">
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
@include('templates.modal.end')