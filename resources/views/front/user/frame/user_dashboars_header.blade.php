<header class="profile-header">
    <div class="container">
        <div class="avatar tac"> <img src="{{ url('/assets/images/user.svg') }}" width="64"> </div>
        <h2 class="name"> {{ user()->full_name }} </h2>
        <span class="label alt green md"> {{ trans('front.all_user_score') }} <strong>{{ pd(floor(user()->sum_receipt_amount / 500000)) }}</strong> </span>
        <div class="profile-tabs">
            <div class="tab {{ $dashboard or '' }}">
                <a href="{{ url_locale('user/dashboard') }}"> <span class="icon-dashboard"></span> {{ trans('manage.dashboard') }} </a>
            </div>
            <div class="tab {{ $orders or '' }}">
                <a href="#"> <span class="icon-cart"></span> {{ trans('front.orders') }} </a>
            </div>
            <div class="tab {{ $profile or '' }}">
                <a href="{{ url_locale('user/profile') }}"> <span class="icon-pencil"></span> {{ trans('front.edit_profile') }} </a>
            </div>
            <div class="tab {{ $accepted_code or '' }}">
                <a href="{{ url_locale('user/drawing') }}"> <span class="icon-tag"></span> {{ trans('front.accepted_codes') }} </a>
            </div>
            <div class="tab {{ $events or '' }}">
                <a href="{{ url_locale('user/events') }}"> <span class="icon-calendar"></span> {{ trans('front.events') }} </a>
            </div>
        </div>
    </div>
</header>