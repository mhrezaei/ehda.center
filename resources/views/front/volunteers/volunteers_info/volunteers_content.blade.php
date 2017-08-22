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
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="row border-2 border-blue pt15 pb15 rounded-corners-5">
                        @if(auth()->guest())
                            {!! Form::open([
                                'url'	=> route_locale('volunteer.register.step1.post') ,
                                'method'=> 'post',
                                'class' => 'clearfix ehda-card-form js',
                                'name' => 'register_form',
                                'id' => 'register_form',
                            ]) !!}

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
                                        'name' => 'email',
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

                            <div class="col-xs-12 pt15">
                                @include('forms.feed')
                            </div>
                            <div id="form-buttons" class="col-xs-12 text-center">
                                @include('forms.button', [
                                    'shape' => 'success',
                                    'label' => trans('forms.button.send'),
                                    'type' => 'submit',
                                ])
                            </div>
                            {!! Form::close() !!}
                        @else
                            <div class="col-xs-12 align-vertical-center align-horizontal-center text-center"
                                 style="min-height: 200px">
                                @if(user()->is_admin())
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="alert alert-info">
                                                {{ trans('front.messages.you_are_volunteer') }}
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <a href="{{ url('manage') }}"
                                               class="btn btn-info">{{ trans('front.volunteer_section.go_section') }}</a>
                                        </div>
                                    </div>
                                @elseif (user()->withDisabled()->is_admin())
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="alert alert-danger">
                                                {{ trans('front.messages.unable_to_register_volunteer') }}
                                            </div>
                                        </div>
                                    </div>
                                @elseif (user()->is_a('card-holder'))
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="alert alert-info">
                                                {{ trans('front.messages.you_are_card_holder') }}
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <a href="{{ route_locale('volunteer.register.step.final.get') }}"
                                               class="btn btn-info">{{ trans('front.volunteer_section.register') }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
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
    </script>
@append