@include('front.frame.header')
<title>
    {{ setting()->ask('site_title')->gain() }} | {{ trans('front.register') }}
</title>
<body class="auth">
<div class="auth-wrapper">
    <div class="auth-col">
        <a href="{{ url_locale('') }}" class="logo"> <img src="{{ url('/assets/images/logo.png') }}"> </a>
    </div>
    <div class="auth-col">
        <h1 class="auth-title"> {{ trans('front.register') }} </h1>
            {!! Form::open([
                'url'	=> '/register/new' ,
                'method'=> 'post',
                'class' => 'form-horizontal js',
                'name' => 'registerForm',
                'id' => 'registerForm',
            ]) !!}

            @include('auth.field', [
                'name' => 'name_first',
                'other' => 'autofocus',
                'type' => 'text',
                'value' => old('name_first'),
                'class' => 'form-required form-persian',
                'min' => 2,
            ])

            @include('auth.field', [
                'name' => 'name_last',
                'type' => 'text',
                'value' => old('name_last'),
                'class' => 'form-required form-persian',
                'min' => 2,
            ])

            @include('auth.field', [
                'name' => 'code_melli',
                'type' => 'text',
                'value' => old('code_melli'),
                'class' => 'form-required form-national',
            ])

            @include('auth.field', [
                'name' => 'mobile',
                'type' => 'text',
                'value' => old('mobile'),
                'class' => 'form-required form-mobile',
            ])

            @include('auth.field', [
                'name' => 'password',
                'type' => 'password',
                'value' => old('password'),
                'class' => 'form-required form-password',
            ])

            @include('auth.field', [
                'name' => 'password2',
                'type' => 'password',
                'value' => old('password2'),
                'class' => 'form-required',
                
            ])

            <div class="tal" style="margin-bottom: 15px;">
                <button class="green block" type="submit"> {{ trans('front.register') }} </button>
            </div>
            @include('forms.feed')
            <hr class="or">
            <div class="tal" style="margin-bottom: 15px;">
                <button onclick="window.location = '{{ url('/login') }}';" class="blue block"> {{ trans('front.member_login') }} </button>
            </div>

        {!! Form::close() !!}
    </div>
</div>
</div>
@include('front.frame.scripts')
</body>

</html>