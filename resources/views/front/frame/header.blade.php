<!DOCTYPE html>
<html lang="{{ getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/template/favicon.ico') }}">
    {{--    {{ null, App::setlocale('en') }}--}}
    @if(isLangRtl())
        {{ Html::style('assets/css/front-style.min.css') }}
    @else
        {{ Html::style('assets/css/front-style-ltr.min.css') }}
    @endif
    @include('front.frame.scripts')
    @yield('head')
</head>