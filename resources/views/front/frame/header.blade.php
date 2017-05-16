<!DOCTYPE html>
<html lang="{{ getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"/>
    {{ Html::style('assets/libs/jquery-ui/jquery-ui.min.css') }}
    {{ Html::style('assets/css/front-additives.min.css') }}
    @if(getLocale() != 'en')
        {{ Html::style('assets/css/front-style.css') }}
        {{ Html::style('assets/css/front-additives-fa.min.css') }}
    @else
        {{ Html::style('assets/css/front-style-en.css') }}
    @endif
    {{ Html::style('assets/libs/font-awesome/css/font-awesome.min.css') }}
    @include('front.frame.scripts')
    @yield('head')
</head>