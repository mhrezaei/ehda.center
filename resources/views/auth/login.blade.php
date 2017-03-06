@include('front.frame.header')
<body class="auth">
<div class="auth-wrapper">
    <div class="auth-col">
        <a href="{{ url_locale('') }}" class="logo"> <img src="{{ url('/assets/images/logo.png') }}"> </a>
    </div>
    <div class="auth-col">
        <h1 class="auth-title"> {{ trans('front.login') }} </h1>
        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <div class="field"> <input type="email" name="email" id="email" placeholder="ایمیل" required autofocus> </div>
            <div class="field"> <input type="password" name="password" id="password" placeholder="رمز عبور" required> </div>

            <div class="tal"> <button class="green block"> {{ trans('front.login') }} </button> </div>
            <hr class="or">
            <div class="tal"> <a href="{{ url('/register') }}" class="green block"> {{ trans('front.not_member_register_now') }} </a> </div>

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
@include('front.frame.scripts')
</body>

</html>