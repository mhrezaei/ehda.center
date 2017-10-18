{!! Form::open([
    'url'	=> route_locale('user.profile.update'),
    'url'	=> route_locale('user.profile.update'),
    'method'=> 'post',
    'class' => 'clearfix ehda-card-form js',
    'name' => 'editForm',
    'id' => 'editForm',
]) !!}

<div class="col-xs-12">
    <div class="form-group">
        <h5 class="form-heading">{{ trans('front.personal_information') }}</h5>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'name_first',
            'value' => user()->name_first,
            'class' => 'form-persian form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.name_first'),
                'minlength' => 2,
            ]
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'name_last',
            'value' => user()->name_last,
            'class' => 'form-persian form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.name_last'),
                'minlength' => 2,
            ]
        ])
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('forms._select-gender', [
            'class' => 'form-select form-required',
            'required' => 1,
            'value' => user()->gender,
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'name_father',
            'value' => user()->name_father,
            'class' => 'form-persian form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.name_father'),
            ]
        ])
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'code_id',
            'value' => user()->code_id,
            'class' => 'form-number',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
            'title' => trans('validation.attributes_example.code_id'),
                'minlength' => 1,
                'maxlength' => 10,
            ]
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'code_melli',
            'value' => user()->code_melli,
            'class' => 'form-national form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.code_melli'),
                'disabled' => 'disabled',
                'minlength' => 10,
                'maxlength' => 10,
            ]
        ])
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @if(user()->birth_date)
            @if(getLocale() == 'fa')
                @php $bd = \Morilog\Jalali\jDate::forge(user()->birth_date) @endphp
            @else
                @php $bd = user()->birth_date @endphp
            @endif
            @php $bdVal = $bd->format('Y/m/d') @endphp
        @else
            @php $bdVal = '' @endphp
        @endif
        {{--@include('forms._birthdate-datepicker', [--}}
            {{--'name' => 'birth_date',--}}
            {{--'class' => 'form-datepicker form-required',--}}
            {{--'value' => $bdVal,--}}
            {{--'dataAttributes' => [--}}
                {{--'toggle' => 'tooltip',--}}
                {{--'placement' => 'top',--}}
            {{--],--}}
            {{--'otherAttributes' => [--}}
                {{--'title' => trans('validation.attributes_example.birth_date'),--}}
            {{--]--}}
        {{--])--}}
        @include('forms._birthdate_3_selects', [
            'name' => 'birth_date',
            'class' => 'form-datepicker form-required',
            'value' => $bdVal,
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.birth_date'),
            ]
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms._states-selectpicker', [
            'name' => 'birth_city' ,
            'value' => user()->birth_city,
            'required' => 1,
            'class' => 'form-required',
        ])
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.select-picker', [
            'name' => 'edu_level',
            'value' => user()->edu_level,
            'class' => 'form-select form-required',
            'blank_value' => '0' ,
            'required' => 1,
            'options' =>
                collect(\Illuminate\Support\Facades\Lang::get('people.edu_level_full'))
                ->map(function ($item, $key) {
                    return ['id' => $key, 'title' => $item];
                }),
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'job',
            'value' => user()->job,
            'class' => 'form-persian form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.job'),
                'minlength' => 2,
            ]
         ])
    </div>
</div>

<div class="form-group">
    <div class="col-xs-12">
        <h5 class="form-heading">{{ trans('front.contact_info') }}</h5>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'mobile',
            'value' => user()->mobile,
            'class' => 'form-mobile form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.mobile'),
                'minlength' => 11,
                'maxlength' => 11,
            ]
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'home_tel',
            'value' => user()->home_tel,
            'class' => 'form-phone form-required',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.home_tel'),
                'minlength' => 11,
                'maxlength' => 11,
            ]
        ])
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('front.forms._states-selectpicker', [
            'name' => 'home_city' ,
            'value' => user()->home_city,
            'required' => 1,
            'class' => 'form-required',
        ])
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'email',
            'value' => user()->email,
            'class' => 'form-email',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.email'),
            ]
        ])
    </div>
</div>

<div class="form-group">
    <div class="col-xs-12">
        <h5 class="form-heading">{{ trans('front.login_info') }}</h5>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'new_password',
            'class' => 'form-password',
            'type' => 'password',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.password'),
                'minlength' => 8,
                'maxlength' => 64,
            ]
        ])

        <div class="col-xs-12">
                        <span class="f12" style="color: dimgray; line-height: 0px;">
                                @include('front.forms.require-sign')
                            درصورت تکمیل جایگزین رمز قبلی میگردد.
                        </span>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        @include('front.forms.input', [
            'name' => 'new_password2',
            'type' => 'password',
            'dataAttributes' => [
                'toggle' => 'tooltip',
                'placement' => 'top',
            ],
            'otherAttributes' => [
                'title' => trans('validation.attributes_example.password2'),
                'minlength' => 8,
                'maxlength' => 64,
            ]
        ])
    </div>
</div>

<div class="row" style="margin-top: 15px;">
    @include('forms.feed')
</div>
<div class="form-group text-center">
    @include('front.forms.button', [
        'shape' => 'success step_one_btn',
        'label' => trans('forms.button.save'),
        'type' => 'submit',
    ])
    @include('front.forms.link-button', [
        'shape' => 'warning step_one_btn',
        'label' => trans('forms.button.cancel'),
        'type' => 'button',
        'link' => route_locale('user.dashboard')
    ])
</div>
{!! Form::close() !!}