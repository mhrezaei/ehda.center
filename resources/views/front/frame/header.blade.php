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
    <script>!function(e,t,a){"use strict";  var s=t.head||t.getElementsByTagName( "head" )[ 0 ], p=t.createElement( "script" ); e.iwmfBadge=a, p.async=true, p.src= "https://c.iwmf.ir/get-code/people-vote-4-2.js?v=10.1", s.appendChild(p) }(this,document,"b-bottom-left");</script>
</head>