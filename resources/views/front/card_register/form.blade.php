{!! Html::script ('assets/site/js/persian-date-0.1.8.min.js') !!}
{!! Html::script ('assets/site/js/persian-datepicker-0.4.5.min.js') !!}
{!! Html::script ('assets/libs/jquery.form.min.js') !!}
{!! Html::script ('assets/js/forms.js') !!}

{!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
{!! HTML::script ('assets/libs/bootstrap-select/bootstrap-select.min.js') !!}
{!! HTML::script ('assets/libs/bootstrap-select/defaults-fa_IR.min.js') !!}


<div class="row">
    <div class="col-xs-12">
        <div class="container">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="row text-center">
                    <img src="{{ url('') }}/assets/site/images/card.png"
                         alt="{{ trans('site.know_menu.organ_donation_card') }}" class="ehda-card-image">
                </div>
                <div class="row">
                    {!! Form::open([
                        'url'	=> '/register/second_step' ,
                        'method'=> 'post',
                        'class' => 'clearfix ehda-card-form js',
                        'name' => 'registerForm',
                        'id' => 'registerForm',
                    ]) !!}

                    <div class="form-group">
                        <div>{{ trans('site.global.personal_data') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'name_first',
                            'min' => 2,
                            'class' => 'form-persian form-required',
                            'required' => 1,
                            'value' => $input['name_first']
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'name_last',
                            'min' => 2,
                            'class' => 'form-persian form-required',
                            'required' => 1,
                            'value' => $input['name_last']
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.select_gender', [
                                'field' => 'gender',
                                'class' => 'form-select form-required',
                                'required' => 1,
                                ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'name_father',
                            'min' => 2,
                            'class' => 'form-persian form-required',
                            'required' => 1,
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'code_id',
                            'min' => 1,
                            'max' => 10,
                            'class' => 'form-number form-required',
                            'required' => 1,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'code_melli',
                            'min' => 10,
                            'max' => 10,
                            'class' => 'form-national form-required',
                            'required' => 1,
                            'value' => $input['code_melli'],
                            'attr' => 'readonly'
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'birth_date',
                            'class' => 'form-datepicker form-required',
                            'required' => 1,
                            'attr' => 'autocomplete=off',
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.select_city' , [
                            'field' => 'birth_city' ,
                            'blank_value' => '0' ,
                            'options' => $states ,
                            'search' => true ,
                            'required' => 1,
                            'class' => 'form-selectpicker form-required',
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.select_edu_level', [
                                'field' => 'edu_level',
                                'class' => 'form-select form-required',
                                'required' => 1,
                                ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'job',
                            'min' => 2,
                            'class' => 'form-persian  form-required',
                            'required' => 1,
                             ])
                        </div>
                    </div>

                    <div class="form-group">
                        <div>{{ trans('site.global.contact_detail') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'tel_mobile',
                            'min' => 11,
                            'max' => 11,
                            'class' => 'form-mobile form-required',
                            'required' => 1,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'home_tel',
                            'min' => 11,
                            'max' => 11,
                            'class' => 'form-phone form-required',
                            'required' => 1,
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.select_city' , [
                            'field' => 'home_city' ,
                            'blank_value' => '0' ,
                            'options' => $states ,
                            'search' => true ,
                            'required' => 1,
                            'class' => 'form-selectpicker form-required',
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'email',
                            'class' => 'form-email',
                            ])
                        </div>
                    </div>

                    <div class="form-group">
                        <div>{{ trans('site.global.login_information_data') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'password',
                            'class' => 'form-password form-required',
                            'required' => 1,
                            'type' => 'password',
                            'min' => 8,
                            'max' => 64,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'password2',
                            'class' => 'form-required',
                            'required' => 1,
                            'type' => 'password',
                            'min' => 8,
                            'max' => 64,
                            ])
                        </div>
                    </div>

{{--                    @include('forms_site.organs_checkbox')--}}
                    <div class="row" style="margin-top: 15px;">
                        @include('forms.feed')
                    </div>
                    <div class="form-group text-center">
                        @include('forms.button', [
                            'shape' => 'success step_one_btn',
                            'label' => trans('forms.button.send'),
                            'type' => 'submit',
                        ])
                        @include('forms.button', [
                            'shape' => 'warning step_one_btn',
                            'label' => trans('forms.button.cancel'),
                            'type' => 'button',
                            'link' => url(''),
                        ])
                    </div>
                    {!! Form::close() !!}

                    @include('site.card_register.db_check_form')

                </div>
            </div>
        </div>
    </div>
</div>