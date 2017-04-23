@include('front.frame.header')
<title>
    {{ setting()->ask('site_title')->gain() }} | {{ trans('front.login') }}
</title>
<body class="auth">
<div class="auth-wrapper">
    <div class="auth-col">
        <a href="{{ url_locale('') }}" class="logo"> <img src="{{ url('/assets/images/logo.png') }}"> </a>
    </div>
    <div class="auth-col">
        <h1 class="auth-title"> {{ trans('front.login') }} </h1>
        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="field"><input type="text" name="code_melli" id="code_melli"
                                      placeholder="{{ trans('validation.attributes.code_melli') }}" required autofocus>
            </div>
            <div class="field"><input type="password" name="password" id="password"
                                      placeholder="{{ trans('validation.attributes.password') }}" required></div>

            <div class="tal">
                <button class="green block"> {{ trans('front.login') }} </button>
            </div>
            <div class="row">
                <div class="col-xs-12 pt15">
                    <a href="{{ url(\App\Providers\SettingServiceProvider::getLocale() . '/password/reset') }}">
                        {{ trans('people.form.recover_password') }}
                    </a>
                </div>
            </div>
            <hr class="or">
            <div class="tal">
                <button onclick="window.location = '{{ url('/register') }}';"
                        class="blue block"> {{ trans('front.not_member_register_now') }} </button>
            </div>

            @if($errors->all())
                <div class="alert alert-danger" style="margin-top: 10px;">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

        </form>
    </div>
</div>
</div>

<!-- Load Scripts -->
{!! Html::script ('assets/js/jquery.js') !!}
{!! Html::script ('assets/js/slick.js') !!}
{!! Html::script ('assets/js/persian.js') !!}
{!! Html::script ('assets/js/app.js') !!}
{!! Html::script ('assets/js/jquery.form.min.js') !!}
{!! Html::script ('assets/js/forms.js') !!}
{!! Html::script ('assets/js/front.js') !!}</body>

</html>