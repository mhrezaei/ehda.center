@section('head')
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
@append

@section('endOfBody')
    <script>
        var bootstrapTooltip = $.fn.tooltip.noConflict(); // return $.fn.tooltip to previously assigned value
    </script>

    {!! Html::script ('assets/libs/bootstrap-select/bootstrap-select.min.js') !!}
    {!! Html::script ('assets/libs/jquery.form.min.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
    {!! Html::script ('assets/libs/jquery-ui/jquery-ui.min.js') !!}
    @include('front.frame.datepicker_assets')

    <script>
        $.fn.tooltip = bootstrapTooltip; // give $().bootstrapTooltip the Bootstrap functionality
        function getReadyForStepTwo() {
            // Make the "code_melli" field readonly
            $('#code_melli').attr('readonly', 'readonly');

            // Changing the flag for discovering step of registration
            $('#step-number').val(2);

            // Insert additional fields for step 2 to the form and show theme
            $('#additional-fields').appendTo('#additional-fields-container').slideDown();
        }
    </script>
@append

<div class="row article">
    <div class="container">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    @if(!user()->exists)
                        {!! Form::open([
                                    'url'	=> 'register/card' ,
                                    'method'=> 'post',
                                    'class' => 'clearfix ehda-card-form js',
                                    'name' => 'register_form_step_one',
                                    'id' => 'register_form_step_one',
                                ]) !!}

                        @include('forms.hidden', [
                            'id' => 'step-number',
                            'name' => 'step',
                            'value' => 1,
                        ])

                        <div class="row border-2 border-blue pt15 pb15">

                            {{ null, $cardImage = setting()->ask('organ_donation_card_image_front')->gain() }}
                            @if($cardImage)
                                <div class="col-xs-8 col-xs-offset-2">
                                    <img src="{{ url($cardImage) }}"
                                         class="img-responsive shadow-m rounded-corners-20 mb20"/>
                                </div>
                            @endif

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div>{{ trans('front.personal_information') }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    @include('front.forms.input', [
                                        'name' => 'name_first',
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
                                <div class="col-xs-12">
                                    @include('front.forms.input', [
                                        'name' => 'name_last',
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
                                <div class="col-xs-12">
                                    @include('front.forms.input', [
                                        'name' => 'code_melli',
                                        'class' => 'form-national form-required',
                                        'dataAttributes' => [
                                            'toggle' => 'tooltip',
                                            'placement' => 'top',
                                        ],
                                        'otherAttributes' => [
                                            'title' => trans('validation.attributes_example.code_melli'),
                                            'minlength' => 10,
                                            'maxlength' => 10,
                                        ]
                                    ])
                                </div>
                            </div>
                            <div id="additional-fields-container" class="row">

                            </div>
                            {{--<div class="col-xs-12">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="security">{{ $captcha['question'] }} <span--}}
                            {{--class="text-danger">*</span></label>--}}
                            {{--<input type="text" class="form-control form-number form-required" id="security"--}}
                            {{--name="security" data-toggle="tooltip" data-placement="top"--}}
                            {{--placeholder="{{ trans('validation.attributes_placeholder.security') }}"--}}
                            {{--title="{{ trans('validation.attributes_example.security') }}" minlength="1"--}}
                            {{--error-value="{{ trans('validation.javascript_validation.security') }}">--}}
                            {{--<input type="hidden" name="key" value="{{$captcha['key']}}">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="col-xs-12 pt15">
                                @include('forms.feed')
                            </div>
                            <div class="col-xs-12 text-center">
                                @include('forms.button', [
                                    'shape' => 'success',
                                    'label' => trans('forms.button.send'),
                                    'type' => 'submit',
                                ])
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div id="additional-fields" style="display: non">
                            <div class="col-xs-12">
                                @include('front.frame.widgets.buttonset', [
                                    'name' => 'gender',
                                    'class' => 'form-select form-required',
                                    'required' => 1,
                                    'options' => [
                                        'test' => 'jflksjfkljfkl'
                                    ],
                                ])
                            </div>
                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'name_father',
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

                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'code_id',
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

                            <div class="col-xs-12">
                                @include('front.forms.jquery-ui-datepicker', [
                                    'name' => 'birth_date',
                                    'class' => 'form-datepicker form-required',
                                    'dataAttributes' => [
                                        'toggle' => 'tooltip',
                                        'placement' => 'top',
                                    ],
                                    'otherAttributes' => [
                                        'title' => trans('validation.attributes_example.birth_date'),
                                    ]
                                ])
                            </div>
                            <div class="col-xs-12">
                                @include('front.forms.select-picker' , [
                                    'name' => 'birth_city' ,
                                    'blank_value' => '0' ,
                                    'options' => $states ,
                                    'search' => true ,
                                    'required' => 1,
                                    'class' => 'form-selectpicker form-required',
                                ])
                            </div>

                            <div class="col-xs-12">
                                {{--@include('front.forms.select_edu_level', [--}}
                                {{--'field' => 'edu_level',--}}
                                {{--'class' => 'form-select form-required',--}}
                                {{--'required' => 1,--}}
                                {{--])--}}
                            </div>

                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'job',
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

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">{{ trans('front.contact_info') }}</div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'mobile',
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

                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'home_tel',
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

                            <div class="col-xs-12">
                                {{--@include('front.forms.select_city' , [--}}
                                {{--'field' => 'home_city' ,--}}
                                {{--'blank_value' => '0' ,--}}
                                {{--'options' => $states ,--}}
                                {{--'search' => true ,--}}
                                {{--'required' => 1,--}}
                                {{--'class' => 'form-selectpicker form-required',--}}
                                {{--])--}}
                            </div>
                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'email',
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

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12">{{ trans('front.login_info') }}</div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'password',
                                    'class' => 'form-password form-required',
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
                            </div>
                            <div class="col-xs-12">
                                @include('front.forms.input', [
                                    'name' => 'password2',
                                    'class' => 'form-required',
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
                    @endif
                </div>
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 text-justify">
                            {!! $post->text !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>