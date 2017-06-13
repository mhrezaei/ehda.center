@extends('auth.frame')

@section('body')
    <div class="login-form bg-lightGray">

        @if(isset($showLogo) and $showLogo)
            {{ null, $logo = setting()->ask('site_logo_small')->gain() }}
            @if($logo)
                <div class="text-center mb10">
                    <img src="{{ url($logo) }}" style="width: 100px">
                </div>
            @endif
        @endif

        @yield('formBox')
    </div>
@endsection