@section('head')
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
    <style>
        a.ehda-card {
            display: none;
        }
    </style>
@append

<div class="row article">
    <div class="container">
        <div class="col-xs-12">
            <div class="row border-2 border-blue pt15 pb15 rounded-corners-5">
                    {!! Form::open([
                        'url'	=> route_locale('volunteer.register.step.final.post') ,
                        'method'=> 'post',
                        'class' => 'clearfix ehda-card-form js',
                        'name' => 'register_form',
                        'id' => 'volunteer_final_step',
                        'novalidate' => 'novalidate',
                    ]) !!}

                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.personal_information') }}</h5>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'name_first',
                                'value' => $currentValues['name_first'],
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
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'name_last',
                                'value' => $currentValues['name_last'],
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
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('forms._select-gender', [
                                'class' => 'form-select form-required',
                                'required' => 1,
                                'value' => $currentValues['gender'],
                            ])
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'name_father',
                                'value' => $currentValues['name_father'],
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
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'code_id',
                                'value' => $currentValues['code_id'],
                                'class' => 'form-number form-required',
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
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'code_melli',
                                'value' => $currentValues['code_melli'],
                                'class' => 'form-national form-required',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.code_melli'),
                                    'readonly' => 'readonly',
                                    'minlength' => 10,
                                    'maxlength' => 10,
                                ]
                            ])
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @if($currentValues['birth_date'])
                                {{ null, $jbd = \Morilog\Jalali\jDate::forge($currentValues['birth_date'])->format('Y/m/d') }}
                            @else
                                {{ null, $jbd = '' }}
                            @endif
                            @include('forms._birthdate-datepicker', [
                                'name' => 'birth_date',
                                'class' => 'form-datepicker form-required',
                                'value' => $jbd,
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.birth_date'),
                                ]
                            ])
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms._states-selectpicker', [
                                'name' => 'birth_city' ,
                                'value' => $currentValues['birth_city'],
                                'required' => 1,
                                'class' => 'form-required',
                            ])
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('forms._select-marital', [
                                'class' => 'form-select form-required',
                                'required' => 1,
                                'value' => $currentValues['marital'],
                            ])
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.educational_information') }}</h5>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.select-picker', [
                                'name' => 'edu_level',
                                'value' => $currentValues['edu_level'],
                                'class' => 'form-select form-required',
                                'required' => 1,
                                'options' =>
                                    collect(\Illuminate\Support\Facades\Lang::get('people.edu_level_full'))
                                    ->map(function ($item, $key) {
                                        return ['id' => $key, 'title' => $item];
                                    }),
                            ])
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'edu_field',
                                'value' => $currentValues['edu_field'],
                                'class' => 'form-required',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.edu_field'),
                                ]
                            ])
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms._states-selectpicker', [
                                'name' => 'edu_city' ,
                                'value' => $currentValues['edu_city'],
                                'required' => 1,
                                'class' => 'form-required',
                            ])
                        </div>
                    </div>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.contact_info') }}</h5>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'email',
                                'value' => $currentValues['email'],
                                'class' => 'form-email form-required',
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

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'mobile',
                                'value' => $currentValues['mobile'],
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
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'tel_emergency',
                                'value' => $currentValues['tel_emergency'],
                                'class' => 'form-phone form-required',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.tel_emergency'),
                                    'minlength' => 11,
                                    'maxlength' => 11,
                                ]
                            ])
                        </div>
                    </div>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.home_info') }}</h5>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms._states-selectpicker', [
                                'name' => 'home_city' ,
                                'value' => $currentValues['home_city'],
                                'required' => 1,
                                'class' => 'form-required',
                            ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'home_address',
                                'value' => $currentValues['home_address'],
                                'class' => 'form-persian form-required',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.home_address'),
                                    'minlength' => 10,
                                ]
                             ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'home_tel',
                                'value' => $currentValues['home_tel'],
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

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'home_postal',
                                'value' => $currentValues['home_postal'],
                                'class' => 'form-number',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.home_postal'),
                                    'minlength' => 10,
                                    'maxlength' => 10,
                                ]
                            ])
                        </div>
                    </div>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.job_info') }}</h5>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'job',
                                'value' => $currentValues['job'],
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

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms._states-selectpicker', [
                                'name' => 'work_city' ,
                                'value' => $currentValues['work_city'],
                            ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'work_address',
                                'value' => $currentValues['work_address'],
                                'class' => 'form-persian',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.work_address'),
                                ]
                             ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'work_tel',
                                'value' => $currentValues['work_tel'],
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.work_tel'),
                                    'minlength' => 11,
                                    'maxlength' => 11,
                                ]
                            ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'work_postal',
                                'value' => $currentValues['work_postal'],
                                'class' => 'form-number',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.work_postal'),
                                    'minlength' => 10,
                                    'maxlength' => 10,
                                ]
                            ])
                        </div>
                    </div>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.supplementary_info') }}</h5>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.select-picker', [
                                'name' => 'familiarization',
                                'label' => trans('validation.attributes.familiarization'),
                                'value' => $currentValues['familiarization'],
                                'class' => 'form-required',
                                'blank_value' => '0' ,
                                'required' => 1,
                                'options' =>
                                    collect(trans('people.familiarization'))
                                    ->map(function ($item, $key) {
                                        return ['id' => $key, 'title' => $item];
                                    }),
                            ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'motivation',
                                'value' => $currentValues['motivation'],
                                'class' => 'form-persian form-required',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.motivation'),
                                ]
                             ])
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="row">
                            @include('front.forms.input', [
                                'name' => 'alloc_time',
                                'value' => $currentValues['alloc_time'],
                                'class' => 'form-number form-required',
                                'dataAttributes' => [
                                    'toggle' => 'tooltip',
                                    'placement' => 'top',
                                ],
                                'otherAttributes' => [
                                    'title' => trans('validation.attributes_example.alloc_time'),
                                ]
                             ])
                        </div>
                    </div>


                    <div class="col-xs-12">
                        <div class="form-group">
                            <h5 class="form-heading">{{ trans('front.your_activity') }}</h5>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                        {{-- @TODO: read logged in user info --}}
                        @include('front.forms.activity-checkboxes')
                    </div>


                    <div class="col-xs-12 pt15 align-horizontal-center">
                        <div class="col-md-8 col-xs-12">
                            @include('forms.feed')
                        </div>
                    </div>
                    <div id="form-buttons" class="col-xs-12 text-center">
                        @include('forms.button', [
                            'shape' => 'success',
                            'label' => trans('forms.button.send'),
                            'type' => 'submit',
                        ])
                    </div>


                    {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@section('endOfBody')
    <script>
        var bootstrapTooltip = $.fn.tooltip.noConflict(); // return $.fn.tooltip to previously assigned value

        $(document).on({
            click: function (e) {
                return false;
            }
        }, '.dropdown-toggle');
    </script>

    {!! Html::script ('assets/libs/bootstrap-select/bootstrap-select.min.js') !!}
    {!! Html::script ('assets/libs/jquery.form.min.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
    {!! Html::script ('assets/libs/jquery-ui/jquery-ui.min.js') !!}
    @include('front.frame.datepicker_assets')

    <script>
        $.fn.tooltip = bootstrapTooltip; // give $().bootstrapTooltip the Bootstrap functionality

        function volunteer_final_step_validate() {
            $check = $("input[name='activity[]']:checked").length;

            if ($check > 0)
            {
                return 0;
            }
            else
            {
                return 'حداقل یکی از فعالیت ها را انتخاب نمائید.';
            }
        }

        /**
         * Thing to do after finish registration volunteer
         */
        function afterRegisterVolunteer() {
            $('#volunteer_final_step').find(':input').prop('disabled', true);
        }
    </script>
@append

