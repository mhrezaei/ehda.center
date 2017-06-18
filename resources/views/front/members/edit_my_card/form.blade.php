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
                        'url'	=> '/members/my_card/edit_process' ,
                        'method'=> 'post',
                        'class' => 'clearfix ehda-card-form js',
                        'name' => 'editForm',
                        'id' => 'editForm',
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
                            'value' => Auth::user()->name_first,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'name_last',
                            'min' => 2,
                            'class' => 'form-persian form-required',
                            'required' => 1,
                            'value' => Auth::user()->name_last,
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.select_gender', [
                                'field' => 'gender',
                                'class' => 'form-select form-required',
                                'required' => 1,
                                'value' => Auth::user()->gender,
                                ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'name_father',
                            'min' => 2,
                            'class' => 'form-persian form-required',
                            'required' => 1,
                            'value' => Auth::user()->name_father,
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
                            'value' => Auth::user()->code_id,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'code_melli',
                            'min' => 10,
                            'max' => 10,
                            'class' => 'form-national form-required',
                            'required' => 1,
                            'value' => Auth::user()->code_melli,
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
                            'value' => jDate::forge(Auth::user()->birth_date)->format('Y/m/d')
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
                            'value' => Auth::user()->birth_city,
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.select_edu_level', [
                                'field' => 'edu_level',
                                'class' => 'form-select form-required',
                                'required' => 1,
                                'value' => Auth::user()->edu_level,
                                ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'job',
                            'min' => 2,
                            'class' => 'form-persian form-required',
                            'required' => 1,
                            'value' => Auth::user()->job,
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
                            'value' => Auth::user()->tel_mobile,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'home_tel',
                            'min' => 11,
                            'max' => 11,
                            'class' => 'form-phone form-required',
                            'required' => 1,
                            'value' => Auth::user()->home_tel,
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
                            'value' => Auth::user()->home_city,
                            ])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'email',
                            'class' => 'form-email',
                            'value' => Auth::user()->email,
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
                            'class' => 'form-password',
                            'type' => 'password',
                            'min' => 8,
                            'max' => 64,
                            ])
                            <span style="color: dimgray; font-size: 10px; line-height: 0px;">درصورت تکمیل جایگزین رمز قبلی میگردد.</span>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            @include('forms_site.input', [
                            'field' => 'password2',
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
                            'label' => trans('forms.button.save'),
                            'type' => 'submit',
                        ])
                        @include('forms.button', [
                            'shape' => 'warning step_one_btn',
                            'label' => trans('forms.button.cancel'),
                            'type' => 'button',
                            'link' => url('/members/my_card'),
                        ])
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>