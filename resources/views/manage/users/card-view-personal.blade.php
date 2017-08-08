<tr><td colspan="2"><hr></td></tr>

{{--
|--------------------------------------------------------------------------
| Code_Melli
|--------------------------------------------------------------------------
| 
--}}

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

{{--
|--------------------------------------------------------------------------
| Birth Info
|--------------------------------------------------------------------------
| Birthday, Birth City, Age
--}}

<tr>
	<td class="head">
		{{ trans('validation.attributes.birth') }}
	</td>
	<td class="body">
		{{ $model->birth_date_on_card }}
		@if($age = $model->age)
			<span class="mh5">
				({{ trans("people.form.n_years_old" , [	"n" => pd($age) ,]) }})
			</span>
		@endif
		<span>
			{{ $model->birth_city_name }}
		</span>
	</td>

</tr>

{{--
|--------------------------------------------------------------------------
| Education
|--------------------------------------------------------------------------
|
--}}

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

{{--
|--------------------------------------------------------------------------
| Home Address
|--------------------------------------------------------------------------
|
--}}

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
