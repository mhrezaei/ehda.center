@section('head')
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
    <style>
        a.ehda-card {
            display: none;
        }
    </style>
@append

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

        /**
         * Thing to do while getting to every steps from every directions
         * @param {int} stepNumber
         */
        function goToStep(stepNumber) {

            // Hide "feeds"
            $('#register_form').find('.form-feed').hide()

            // Changing the flag for discovering step of registration
            $('#step-number').val(stepNumber);

            switch (stepNumber) {
                case 1:
                    // Disabling additional input
                    $('#additional-fields').find(':input').attr('disabled', 'disabled');
                    break;
                case 2:
                    // Make the "code_melli" field readonly
                    $('#code_melli').attr('readonly', 'readonly');
                    break;
                case 3:
                    // Showing related buttons
                    $('#form-buttons').hide();
                    $('#last-step-buttons').show();
                    break;
            }
        }

        /**
         * Thing to do while getting to each step from its previous step
         * @param {int} stepNumber
         */
        function upToStep(stepNumber) {
            goToStep(stepNumber);

            switch (stepNumber) {
                case 1:
                    break;

                case 2:
                    // Enabling additional input
                    $('#additional-fields').find(':input').removeAttr('disabled');

                    // Show the additional fields for step 2 to the form
                    $('#additional-fields').slideDown();

                    // Refreshing view of buttonsets
                    $(".form-buttonset").each(function () {
                        // var options = $(this).dataStartsWith(juiDataPrefix.buttonset);
                        $(this).buttonset().buttonset('refresh');
                    });

                    // Showing cancel button to get back to step 1
                    $('#cancel-button').show();

                    break;
                case 3:
                    // Making all inputs in form readonly
                    $('#register_form').find(':input').attr('readonly', 'readonly');
                    $('#register_form').find('.form-group').css('pointer-events', 'none');
                    break;
            }
        }

        function downToStep(stepNumber) {
            switch (stepNumber) {
                case 1:
                    // Hide additional fields (the fields related to step 2)
                    $('#additional-fields').slideUp();

                    // Make the "code_melli" field writable
                    $('#code_melli').removeAttr('readonly');

                    // Reset values of additional inputs in
                    $('#additional-fields').find(':input').each(function () {
                        if ($(this).is(':radio')) {
                            $(this).prop('checked', false);
                        } else {
                            $(this).val('');
                        }

                        $(this).change();
                    });

                    // Hiding cancel button
                    $('#cancel-button').hide();
                    break;
                case 2:
                    // Making all inputs in form writable
                    console.log('here');
                    $('#register_form').find(':input').removeAttr('readonly');
                    $('#register_form').find('.form-group').css('pointer-events', 'auto');

                    // Showing related buttons
                    $('#form-buttons').show();
                    $('#last-step-buttons').hide();
                    break;
            }

            goToStep(stepNumber);
        }

        function downStep() {
            downToStep(parseInt($('#step-number').val()) - 1);
        }

        $(document).ready(function () {
            upToStep(1);
        });
    </script>
@append

<div class="row article">
    <div class="container">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    @if(!user()->exists)
                        {!! Form::open([
                            'url'	=> route_locale('register_card.post') ,
                            'method'=> 'post',
                            'class' => 'clearfix ehda-card-form js',
                            'name' => 'register_form',
                            'id' => 'register_form',
                        ]) !!}

                        @include('forms.hidden', [
                            'id' => 'step-number',
                            'name' => '_step',
                            'value' => 1,
                        ])

                        <div class="row border-2 border-blue pt15 pb15 rounded-corners-5">

                            {{ null, $cardImage = setting()->ask('organ_donation_card_image_front')->gain() }}
                            @if($cardImage)
                                <div class="col-xs-8 col-xs-offset-2">
                                    <img src="{{ url($cardImage) }}"
                                         class="img-responsive shadow-m rounded-corners-20 mb20"/>
                                </div>
                            @endif

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <h5 class="form-heading">{{ trans('front.personal_information') }}</h5>
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
                            <div id="additional-fields" style="display: none">
                                <div class="col-xs-12">
                                    @include('forms._select-gender', [
                                        'class' => 'form-select form-required',
                                        'required' => 1,
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

                                {{--<div class="col-xs-12">--}}
                                {{--@include('front.forms.input', [--}}
                                {{--'name' => 'code_id',--}}
                                {{--'class' => 'form-number form-required',--}}
                                {{--'dataAttributes' => [--}}
                                {{--'toggle' => 'tooltip',--}}
                                {{--'placement' => 'top',--}}
                                {{--],--}}
                                {{--'otherAttributes' => [--}}
                                {{--'title' => trans('validation.attributes_example.code_id'),--}}
                                {{--'minlength' => 1,--}}
                                {{--'maxlength' => 10,--}}
                                {{--]--}}
                                {{--])--}}
                                {{--</div>--}}

                                <div class="col-xs-12">
                                    @include('forms._birthdate-datepicker', [
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
                                    @include('front.forms._states-selectpicker', [
                                        'name' => 'birth_city' ,
                                        'required' => 1,
                                        'class' => 'form-required',
                                    ])
                                </div>

                                <div class="col-xs-12">
                                    @include('front.forms.select-picker', [
                                        'name' => 'edu_level',
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
                                        <div class="col-xs-12">
                                            <h5 class="form-heading">{{ trans('front.contact_info') }}</h5>
                                        </div>
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
                                    @include('front.forms._states-selectpicker', [
                                        'name' => 'home_city' ,
                                        'required' => 1,
                                        'class' => 'form-required',
                                    ])
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
                                        <div class="col-xs-12">
                                            <h5 class="form-heading">{{ trans('front.login_info') }}</h5>
                                        </div>
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
                            <div id="form-buttons" class="col-xs-12 text-center">
                                @include('forms.button', [
                                    'shape' => 'success',
                                    'label' => trans('forms.button.send'),
                                    'type' => 'submit',
                                ])
                                @include('forms.button', [
                                    'id' => 'cancel-button',
                                    'shape' => 'warning',
                                    'label' => trans('forms.button.cancel'),
                                    'type' => 'button',
                                    'link' => 'downStep()',
                                    'style' => 'display:none'
                                ])
                            </div>
                            <div id="last-step-buttons" class="col-xs-12 text-center" style="display: none">
                                <button class="img-btn" type="submit">
                                    <img src="{{ url('assets/images/template/join_ngo.png') }}" />
                                </button>
                                <br class="clear-fix"/>
                                @include('forms.button', [
                                    'shape' => 'danger',
                                    'label' => trans('forms.feed.no_im_wrong'),
                                    'type' => 'button',
                                    'link' => 'downStep()',
                                ])
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @endif
                </div>
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 text-justify">
                            <h2 style="margin-top: 0">{{ $post->title }}</h2>
                            {!! $post->text !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>