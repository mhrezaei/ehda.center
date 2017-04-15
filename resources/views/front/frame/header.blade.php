<!DOCTYPE html>
<html lang="{{ getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"/>
    @if(getLocale() != 'en')
        {{ Html::style('assets/css/front-style.css') }}
    @else
        {{ Html::style('assets/css/front-style-en.css') }}
    @endif
    @include('front.frame.scripts')
    @yield('head')
</head>