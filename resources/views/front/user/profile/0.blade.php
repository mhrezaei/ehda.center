@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.edit_profile') }}</title>
    @include('front.frame.datepicker_assets')
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('front.edit_profile'), url_locale('user/profile')],
        ]
    ])
@endsection

@section('content')
    <div class="page-content profile">
        @include('front.user.frame.user_dashboars_header', ['profile' => 'active'])
        @include('front.user.profile.form')
    </div>
@endsection