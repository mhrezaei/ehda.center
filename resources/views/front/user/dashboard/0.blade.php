@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('manage.dashboard') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('manage.dashboard'), url_locale('user/dashboard')],
        ]
    ])
@endsection

@section('content')
    <div class="page-content profile">
        @include('front.user.frame.user_dashboars_header', ['dashboard' => 'active'])
        @if(setting()->ask('dashboard_comment')->gain())
            @include('front.user.dashboard.add_comment')
        @endif
    </div>
@endsection