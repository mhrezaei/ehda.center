@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.events') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('front.events'), url_locale('user/events')],
        ]
    ])
@endsection

@section('content')
    <div class="page-content profile">
        @include('front.user.frame.user_dashboars_header', ['events' => 'active'])
        @include('front.user.events.content')
    </div>
@endsection