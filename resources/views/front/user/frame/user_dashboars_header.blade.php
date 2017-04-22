<header class="profile-header">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="avatar tac"><img src="{{ url('/assets/images/user.svg') }}" width="64"></div>
                <h2 class="name"> {{ user()->full_name }} </h2>
                <span class="label alt green md"> {{ trans('front.all_user_score') }}
                    <strong>{{ pd(floor(user()->sum_receipt_amount / 500000)) }}</strong>
                </span>
            </div>
            @if(!arrayHasRequired(\App\Models\User::$required_fields, user()->toArray()))
                <div class="col-xs-12 pt20">
                    <div class="row">
                        <div class="alert alert-danger text-right">
                            {{ trans('front.profile_messages.not_enough_information') }}
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="{{ url(\App\Providers\SettingServiceProvider::getLocale() .'/user/profile') }}">{{ trans('front.edit_profile') }}</a>
                        </div>
                    </div>
                </div>
            @endif
            <div class="profile-tabs col-xs-12">
                <div class="tab {{ $dashboard or '' }}">
                    <a href="{{ url_locale('user/dashboard') }}"> <span
                                class="icon-dashboard"></span> {{ trans('manage.dashboard') }} </a>
                </div>
                <div class="tab {{ $orders or '' }}">
                    <a href="#"> <span class="icon-cart"></span> {{ trans('front.orders') }} </a>
                </div>
                <div class="tab {{ $profile or '' }}">
                    <a href="{{ url_locale('user/profile') }}"> <span
                                class="icon-pencil"></span> {{ trans('front.edit_profile') }} </a>
                </div>
                <div class="tab {{ $accepted_code or '' }}">
                    <a href="{{ url_locale('user/drawing') }}"> <span
                                class="icon-tag"></span> {{ trans('front.accepted_codes') }} </a>
                </div>
                <div class="tab {{ $events or '' }}">
                    <a href="{{ url_locale('user/events') }}"> <span
                                class="icon-calendar"></span> {{ trans('front.events') }} </a>
                </div>
            </div>
        </div>
    </div>
</header>