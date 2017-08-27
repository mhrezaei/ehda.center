@extends('front.frame.frame')

@php $staticPost->spreadMeta() @endphp

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.send_works') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
                'group' => $staticPost->header_title,
                'groupColor' => 'green',
                'category' => $staticPost->category_title,
                'title' => $staticPost->title,
            ])
        <div class="container content">
            <div class="row">
                <div class="col-xs-12">
                    {!! $sendingArea !!}
                </div>
                @include('front.test.works.sharing')
            </div>
        </div>
    </div>
@endsection