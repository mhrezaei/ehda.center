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
            'url' => url(\App\Providers\SettingServiceProvider::getLocale() . '/password/reset'),
            'method'=> 'post',
            'class' => 'js',
            'name' => 'editForm',
            'id' => 'editForm',
            'style' => 'padding: 15px;',
        ]) !!}

        <div class="row">
            @include('forms.input',[
                'name' => 'code_melli',
                'label' => false,
                'placeholder' => trans('validation.attributes.code_melli'),
                'containerClass' => 'field',
            ])

            @include('front.frame.widgets.radio',[
                'name' => 'type',
                'value' => 'email',
                'options' => [
                    'email' => trans('validation.attributes.email'),
                    'mobile' => trans('validation.attributes.mobile'),
                ],
                'label' => false,
            ])

            @include('forms.input',[
                'name' => 'email',
                'label' => false,
                'placeholder' => trans('validation.attributes.email'),
                'container' => [
                    'class' => 'field',
                    'id' => 'email-container',
                ],
            ])

            @include('forms.input',[
                'name' => 'mobile',
                'label' => false,
                'placeholder' => trans('validation.attributes.mobile'),
                'container' => [
                    'class' => 'field',
                    'id' => 'mobile-container',
                    'other' => [
                        'style' => 'display:none',
                    ]
                ],
            ])

        </div>


        {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
        {{--<label for="email" class="col-md-4 control-label">E-Mail Address</label>--}}

        {{--<div class="col-md-6">--}}
        {{--<input id="email" type="email" class="form-control" name="email"--}}
        {{--value="{{ old('email') }}" required>--}}

        {{--@if ($errors->has('email'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('email') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
        {{--<div class="col-md-6 col-md-offset-4">--}}
        {{--<button type="submit" class="btn btn-primary">--}}
        {{--Send Password Reset Link--}}
        {{--</button>--}}
        {{--</div>--}}
        {{--</div>--}}

        <div class="tal pb15">
            <button class="green block"> {{ trans('people.form.send_password_reset_link') }} </button>
        </div>

        <div class="tal pb15">
            <a href="{{ url(\App\Providers\SettingServiceProvider::getLocale() . '/password/token/code') }}">
                <button type="button" class="green block"> {{ trans('people.form.have_a_code') }} </button>
            </a>
        </div>

        @include('forms.feed')
        {!! Form::close() !!}
    </div>
</div>
</div>
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

</body>

</html>