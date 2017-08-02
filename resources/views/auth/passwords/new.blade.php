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
        'url' => url(\App\Providers\SettingServiceProvider::getLocale() . '/password/new'),
        'method'=> 'post',
        'class' => 'js',
        'name' => 'editForm',
        'id' => 'editForm',
    ]) !!}

    <div class="row">
        @include('forms.input',[
            'name' => 'new_password',
            'type' => 'password',
            'label' => false,
            'placeholder' => trans('validation.attributes.new_password'),
            'containerClass' => 'field',
            'class' => 'input-lg',
            'icon' => 'key',
        ])
    </div>

    <div class="row">
        @include('forms.input',[
            'name' => 'new_password2',
            'type' => 'password',
            'label' => false,
            'placeholder' => trans('validation.attributes.new_password2'),
            'containerClass' => 'field',
            'class' => 'input-lg',
            'icon' => 'key',
        ])
    </div>

    <div class="pb15 text-center">
        <button class="btn btn-green btn-lg"> {{ trans('forms.button.save') }} </button>
    </div>

    @include('forms.feed')
    {!! Form::close() !!}
@endsection

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
@endsection