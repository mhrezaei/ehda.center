@include('front.frame.header')
<title>
    {{ setting()->ask('site_title')->gain() }} | {{ trans('people.form.recover_password') }}
</title>
<body class="auth">
<div class="auth-wrapper">
    <div class="auth-col">
        <a href="{{ url_locale('') }}" class="logo"> <img src="{{ url('/assets/images/logo.png') }}"> </a>
    </div>
    <div class="auth-col">
        <h1 class="auth-title"> {{ trans('people.form.recover_password') }} </h1>
        {!! Form::open([
            'url' => url(\App\Providers\SettingServiceProvider::getLocale() . '/password/token'),
            'method'=> 'post',
            'class' => 'js',
            'name' => 'editForm',
            'id' => 'editForm',
            'style' => 'padding: 15px;',
        ]) !!}

        <div class="row">
            @include('forms.input',[
                'name' => 'password_reset_token',
                'label' => false,
                'placeholder' => trans('validation.attributes.password_reset_token'),
                'containerClass' => 'field',
            ])

        </div>

        <div class="tal pb15">
            <button class="green block"> {{ trans('people.form.check_password_token') }} </button>
        </div>

        @include('forms.feed')
        {!! Form::close() !!}
    </div>
</div>
</div>
</body>

</html>