@extends('auth.form_frame', ['showLogo' => true])

@section('head')
    @parent
    <title>
        {{ setting()->ask('site_title')->gain() }} | {{ trans('people.form.recover_password') }}
    </title>
@endsection
@include('front.frame.open_graph_meta_tags', ['description' => trans('front.login')])

@section('formBox')
    {!! Form::open([
            'url' => url(\App\Providers\SettingServiceProvider::getLocale() . '/password/reset'),
            'method'=> 'post',
            'class' => 'js',
            'name' => 'editForm',
            'id' => 'editForm',
        ]) !!}

    <div class="row">
        @include('front.forms.input',[
            'name' => 'code_melli',
            'label' => false,
            'placeholder' => trans('validation.attributes.code_melli'),
            'containerClass' => 'field',
            'class' => 'input-lg',
            'icon' => 'id-card',
        ])

        @include('front.frame.widgets.radio',[
            'name' => 'type',
            'value' => $errors->has('mobile') ? 'mobile' :'email',
            'options' => [
                'email' => trans('validation.attributes.email'),
                'mobile' => trans('validation.attributes.mobile'),
            ],
            'label' => false,
        ])

        @include('front.forms.input',[
            'name' => 'email',
            'label' => false,
            'placeholder' => trans('validation.attributes.email'),
            'class' => 'input-lg',
            'container' => [
                'class' => 'field',
                'id' => 'email-container',
            ],
            'icon' => 'envelope',
        ])

        @include('front.forms.input',[
            'name' => 'mobile',
            'label' => false,
            'placeholder' => trans('validation.attributes.mobile'),
            'class' => 'input-lg',
            'container' => [
                'class' => 'field',
                'id' => 'mobile-container',
                'other' => [
                    'style' => 'display:none',
                ],
            ],
            'icon' => 'phone',
        ])

    </div>

    <div class="tal pb15">
        <button class="btn btn-green btn-block btn-lg"> {{ trans('people.form.send_password_reset_link') }} </button>
    </div>

    <div class="tal pb15">
        <a href="{{ url(\App\Providers\SettingServiceProvider::getLocale() . '/password/token/code') }}">
            <button type="button" class="btn btn-blue btn-block"> {{ trans('people.form.have_a_code') }} </button>
        </a>
    </div>

    @include('front.forms.feed')
    {!! Form::close() !!}
@endsection

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
    <script>
        $(document).ready(function () {
            $('input[type=radio][name=type]').change(function () {
                if ($(this).is(':checked')) {
                    if ($(this).val() == 'email') {
                        $('#email-container').show();
                        $('#mobile-container').hide();
                    } else if ($(this).val() == 'mobile') {
                        $('#mobile-container').show();
                        $('#email-container').hide();
                    }
                }
            }).change();
        });
    </script>
@endsection