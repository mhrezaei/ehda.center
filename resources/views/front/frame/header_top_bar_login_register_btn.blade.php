@if(!user()->exists)
    <a href="{{ url('/login') }}" class="auth-link"> {{ trans('front.login_register') }} </a>
@endif