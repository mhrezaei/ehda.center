@extends('front.frame.frame')

@php
    $pageTitle = setting()->ask('site_title')->gain() .
        ' | ' .
        trans('front.profile_phrases.user_profile', ['user' => user()->full_name])
@endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    {{ null, $positionInfo = (isset($positionInfo) and is_array($positionInfo)) ? $positionInfo : [] }}
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => trans('front.main-menu.items.join'),
            'groupColor' => 'green',
            'category' => trans('front.profile_phrases.profile'),
            'categoryColor' => 'green',
            'title' => user()->full_name,
        ] + $positionInfo )
        <div class="container content pb20">
            @yield('inner-content')
        </div>
    </div>
@append