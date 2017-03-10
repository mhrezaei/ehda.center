@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.home') }}</title>
@endsection

@section('navbar')
    @include('front.frame.navbar',
    [
        'array' =>
        [
            [trans('front.home'), url_locale('')],
            [trans('front.accepted_codes'), url_locale('user/drawing')],
        ]
    ])
@endsection

@section('content')
    <div class="page-content profile">
        @include('front.user.frame.user_dashboars_header', ['accepted_code' => 'active'])
        @include('front.user.drawing.content')
    </div>
@endsection