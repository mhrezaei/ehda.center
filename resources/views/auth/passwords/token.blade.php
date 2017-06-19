@extends('auth.form_frame', ['showLogo' => true])

@section('head')
    @parent
    <title>
        {{ setting()->ask('site_title')->gain() }} | {{ trans('people.form.recover_password') }}
    </title>
    @include('front.frame.open_graph_meta_tags', ['description' => trans('front.login')])
@endsection

@section('formBox')
    {!! Form::open([
           'url' => url(\App\Providers\SettingServiceProvider::getLocale() . '/password/token'),
           'method'=> 'post',
           'class' => 'js',
           'name' => 'editForm',
           'id' => 'editForm',
       ]) !!}

    @if($haveCode or !session()->get('resetingPasswordNationalId'))
        <div class="row">
            @include('front.forms.input',[
                'name' => 'code_melli',
                'label' => false,
                'placeholder' => trans('validation.attributes.code_melli'),
                'class' => 'form-required form-national input-lg',
                'containerClass' => 'field',
                'error_value' => trans('validation.javascript_validation.code_melli'),
                'icon' => 'id-card',
            ])
        </div>
    @endif

    <div class="row">
        @include('front.forms.input',[
            'name' => 'password_reset_token',
            'label' => false,
            'placeholder' => trans('validation.attributes.password_reset_token'),
            'class' => 'form-required input-lg',
            'containerClass' => 'field',
            'error_value' => trans('validation.javascript_validation.password_reset_token'),
            'icon' => 'key',
        ])
    </div>

    <div class="pb15 text-center">
        <button class="btn btn-green btn-lg"> {{ trans('people.form.check_password_token') }} </button>
    </div>

    @include('forms.feed')
    {!! Form::close() !!}
@endsection

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
@endsection