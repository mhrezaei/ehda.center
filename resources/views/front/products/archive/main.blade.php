@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $postType->titleIn(getLocale()) @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => trans('front.tutorials.singular'),
            'groupColor' => 'green',
            'categoryColor' => 'green',
        ])
        <div class="row gallery-archive">
            <div class="container">
                @include('front.products.archive.carousel')
                @include('front.products.archive.content')
            </div>
        </div>
    </div>
@append
