@extends('front.frame.frame')

@php $staticPost->spreadMeta() @endphp

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.send_works') }}</title>
@append

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