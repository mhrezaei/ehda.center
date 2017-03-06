@if(user()->exists)
<div class="dropdown bottom-left user"> 
    <a href="#" class="dropdown-toggle">
        <span>
            <img src="{{ url('/assets/images/user.svg') }}">
        </span> {{ user()->full_name }} 
    </a>
    <div class="menu"> 
        <a href="{{ url_locale('user/orders') }}"> {{ trans('front.orders') }} </a>
        <a href="{{ url_locale('user/profile') }}"> {{ trans('front.profile') }} </a>
        <a href="{{ url_locale('user/setting') }}"> {{ trans('front.setting') }} </a>
        <a href="{{ url('/logout') }}"> {{ trans('front.log_out') }} </a>
    </div>
</div>
@endif