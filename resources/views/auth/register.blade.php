@extends('auth.form_frame', ['showLogo' => true])

@section('head')
    @parent
    <title>
        {{ setting()->ask('site_title')->gain() }} | {{ trans('front.register') }}
    </title>
    @include('front.frame.open_graph_meta_tags', ['description' => trans('front.register')])
@endsection

@section('formBox')

    {!! Form::open([
        'url'	=> '/register/new' ,
        'method'=> 'post',
        'class' => 'form-horizontal js',
        'name' => 'registerForm',
        'id' => 'registerForm',
    ]) !!}

    <div class="row">
        @include('front.forms.input',[
            'name' => 'name_first',
            'label' => false,
            'placeholder' => trans('validation.attributes.name_first'),
            'value' => old('name_first'),
            'containerClass' => 'field',
            'class' => 'input-lg form-persian form-required',
            'icon' => 'user',
            'extra' => 'autofocus',
        ])

        @include('front.forms.input',[
            'name' => 'name_last',
            'label' => false,
            'placeholder' => trans('validation.attributes.name_last'),
            'value' => old('name_last'),
            'containerClass' => 'field',
            'class' => 'input-lg form-persian form-required',
            'icon' => 'user',
        ])

        @include('front.forms.input',[
            'name' => 'code_melli',
            'label' => false,
            'placeholder' => trans('validation.attributes.code_melli'),
            'value' => old('code_melli'),
            'containerClass' => 'field',
            'class' => 'input-lg form-national form-required',
            'icon' => 'id-card',
        ])

        @include('front.forms.input',[
            'name' => 'mobile',
            'label' => false,
            'placeholder' => trans('validation.attributes.mobile'),
            'value' => old('mobile'),
            'containerClass' => 'field',
            'class' => 'input-lg form-mobile form-required',
            'icon' => 'phone',
        ])

        @include('front.forms.input',[
            'name' => 'email',
            'label' => false,
            'placeholder' => trans('validation.attributes.email'),
            'value' => old('email'),
            'containerClass' => 'field',
            'class' => 'input-lg form-email',
            'icon' => 'envelope',
        ])

        @include('front.forms.input',[
            'name' => 'password',
            'id' => 'password',
            'label' => false,
            'type' => 'password',
            'placeholder' => trans('validation.attributes.password'),
            'value' => old('password'),
            'containerClass' => 'field',
            'class' => 'input-lg form-password form-required',
            'icon' => 'key',
        ])

        @include('front.forms.input',[
            'name' => 'password2',
            'id' => 'password2',
            'label' => false,
            'type' => 'password',
            'placeholder' => trans('validation.attributes.password2'),
            'value' => old('password2'),
            'containerClass' => 'field',
            'class' => 'input-lg form-required',
            'icon' => 'key',
        ])
    </div>

    <div class="row text-center">
        <div class="col-xs-12 mb10">
            <div class="row">
                <button class="btn btn-lg btn-block btn-green"> {{ trans('front.register') }} </button>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                @include('forms.feed')
            </div>
        </div>
        <div class="col-xs-12 mb10">
            <div class="row">
                <a type="button" href="{{ route('login') }}"
                   class="btn btn-block btn-blue"> {{ trans('front.member_login') }} </a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
@endsection